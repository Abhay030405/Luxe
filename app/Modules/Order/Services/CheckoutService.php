<?php

declare(strict_types=1);

namespace App\Modules\Order\Services;

use App\Models\Address;
use App\Modules\Cart\Repositories\Contracts\CartRepositoryInterface;
use App\Modules\Order\DTOs\CartItemDTO;
use App\Modules\Order\DTOs\CheckoutDTO;
use App\Modules\Order\DTOs\CreateOrderDTO;
use App\Modules\Order\DTOs\OrderSummaryDTO;
use App\Modules\Order\Events\OrderPlaced;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Repositories\OrderRepository;
use App\Modules\Product\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

/**
 * CheckoutService handles the critical cart-to-order conversion.
 * This is the MOST IMPORTANT service in the checkout flow.
 * All operations are atomic - either everything succeeds or nothing is saved.
 */
class CheckoutService
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly OrderRepository $orderRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly VendorOrderService $vendorOrderService,
    ) {}

    /**
     * Validate checkout data and prepare order summary.
     * This is called before placing the order to show preview.
     */
    public function prepareCheckout(int $userId, int $addressId): OrderSummaryDTO
    {
        // Step 1: Validate cart not empty
        $cartItems = $this->cartRepository->getUserCartItems($userId);

        if ($cartItems->isEmpty()) {
            throw new InvalidArgumentException('Cart is empty. Cannot proceed to checkout.');
        }

        // Step 2: Validate address belongs to user
        $address = Address::where('id', $addressId)
            ->where('user_id', $userId)
            ->first();

        if (! $address) {
            throw new InvalidArgumentException('Invalid address selected.');
        }

        // Step 3: Validate all products and stock
        $this->validateCartItemsForCheckout($cartItems);

        // Step 4: Convert cart items to DTOs
        $items = $cartItems->map(fn ($cartItem) => CartItemDTO::fromCartItem($cartItem))->toArray();

        // Step 5: Calculate totals
        $taxRate = 0.0; // TODO: Get from config or calculate based on address
        $shippingFee = 0.0; // TODO: Calculate based on address/weight

        return OrderSummaryDTO::fromCartItems($items, $taxRate, $shippingFee);
    }

    /**
     * Place order - THE CRITICAL ATOMIC OPERATION.
     * Either everything succeeds or everything is rolled back.
     */
    public function placeOrder(CheckoutDTO $checkoutDTO): Order
    {
        try {
            return DB::transaction(function () use ($checkoutDTO) {
                // Step 1: Validate cart not empty
                $cartItems = $this->cartRepository->getUserCartItems($checkoutDTO->userId);

                if ($cartItems->isEmpty()) {
                    throw new InvalidArgumentException('Cart is empty.');
                }

                // Step 2: Validate stock availability one final time
                $this->validateCartItemsForCheckout($cartItems);

                // Step 3: Convert cart items to order item DTOs
                $items = $cartItems->map(
                    fn ($cartItem) => CartItemDTO::fromCartItem($cartItem)
                )->toArray();

                // Step 4: Generate unique order number
                $orderNumber = $this->orderRepository->generateOrderNumber();

                // Step 5: Create order DTO
                $createOrderDTO = new CreateOrderDTO(
                    userId: $checkoutDTO->userId,
                    orderNumber: $orderNumber,
                    status: \App\Shared\Enums\OrderStatus::Pending,
                    subtotal: $checkoutDTO->subtotal,
                    tax: $checkoutDTO->tax,
                    shippingFee: $checkoutDTO->shippingFee,
                    totalAmount: $checkoutDTO->total,
                    addressSnapshot: $checkoutDTO->getAddressSnapshot(),
                    items: $items,
                    customerNotes: $checkoutDTO->customerNotes,
                );

                // Step 6: Create order with items (atomic operation in repository)
                $order = $this->orderRepository->createOrderWithItems($createOrderDTO);

                // Step 7: Split order into vendor orders
                $this->vendorOrderService->splitOrderIntoVendorOrders($order);

                // Step 8: Decrement product stock
                foreach ($items as $item) {
                    $this->decrementProductStock($item->productId, $item->quantity);
                }

                // Step 9: Clear cart
                $this->cartRepository->clearUserCart($checkoutDTO->userId);

                // Step 10: Dispatch order placed event
                event(new OrderPlaced($order));

                // Step 11: Log successful order
                Log::info('Order placed successfully', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $checkoutDTO->userId,
                    'total_amount' => $order->total_amount,
                ]);

                return $order;
            });
        } catch (\Exception $e) {
            // Log error
            Log::error('Order placement failed', [
                'user_id' => $checkoutDTO->userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw as RuntimeException
            throw new RuntimeException('Failed to place order: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * Validate all cart items for checkout.
     * Ensures products exist, are active, and have sufficient stock.
     */
    private function validateCartItemsForCheckout($cartItems): void
    {
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            // Check product still exists
            if (! $product) {
                throw new InvalidArgumentException(
                    'Product in cart no longer exists. Please remove it and try again.'
                );
            }

            // Check product is active
            if ($product->status !== 'active') {
                throw new InvalidArgumentException(
                    "Product '{$product->name}' is no longer available. Please remove it from cart."
                );
            }

            // Check stock availability
            if (! $product->isInStock() || $product->stock_quantity < $cartItem->quantity) {
                throw new InvalidArgumentException(
                    "Insufficient stock for product '{$product->name}'. Available: {$product->stock_quantity}, Required: {$cartItem->quantity}"
                );
            }
        }
    }

    /**
     * Decrement product stock after order placement.
     */
    private function decrementProductStock(int $productId, int $quantity): void
    {
        $product = $this->productRepository->findById($productId);

        if (! $product) {
            throw new RuntimeException("Product {$productId} not found during stock decrement");
        }

        $newStock = $product->stock_quantity - $quantity;

        if ($newStock < 0) {
            throw new RuntimeException("Negative stock would result for product {$productId}");
        }

        // Update stock
        $product->stock_quantity = $newStock;
        $product->save();

        Log::info('Product stock decremented', [
            'product_id' => $productId,
            'quantity_sold' => $quantity,
            'new_stock' => $newStock,
        ]);
    }

    /**
     * Check if user can proceed to checkout.
     */
    public function canCheckout(int $userId): bool
    {
        $cartItems = $this->cartRepository->getUserCartItems($userId);

        if ($cartItems->isEmpty()) {
            return false;
        }

        try {
            $this->validateCartItemsForCheckout($cartItems);

            return true;
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Get checkout validation errors.
     */
    public function getCheckoutErrors(int $userId): array
    {
        $errors = [];
        $cartItems = $this->cartRepository->getUserCartItems($userId);

        if ($cartItems->isEmpty()) {
            $errors[] = 'Your cart is empty.';

            return $errors;
        }

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            if (! $product) {
                $errors[] = 'A product in your cart no longer exists.';

                continue;
            }

            if ($product->status !== 'active') {
                $errors[] = "'{$product->name}' is no longer available.";
            }

            if (! $product->isInStock() || $product->stock_quantity < $cartItem->quantity) {
                $errors[] = "Insufficient stock for '{$product->name}'. Available: {$product->stock_quantity}";
            }
        }

        return $errors;
    }
}
