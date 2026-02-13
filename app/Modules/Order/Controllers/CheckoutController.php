<?php

declare(strict_types=1);

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Modules\Cart\Repositories\Contracts\CartRepositoryInterface;
use App\Modules\Order\DTOs\CartItemDTO;
use App\Modules\Order\DTOs\CheckoutDTO;
use App\Modules\Order\Requests\PlaceOrderRequest;
use App\Modules\Order\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CheckoutController handles the checkout process.
 * Displays checkout page, processes order placement.
 */
class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkoutService,
        private readonly CartRepositoryInterface $cartRepository,
    ) {}

    /**
     * Display the checkout page.
     * Shows address selection and order summary.
     */
    public function index(): View|RedirectResponse
    {
        $userId = Auth::id();

        // Check if cart is empty
        $cartItems = $this->cartRepository->getUserCartItems($userId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add items before checkout.');
        }

        // Check for checkout errors (stock issues, etc.)
        $checkoutErrors = $this->checkoutService->getCheckoutErrors($userId);

        if (! empty($checkoutErrors)) {
            return redirect()->route('cart.index')
                ->with('error', 'Please fix the following issues before checkout:')
                ->with('checkout_errors', $checkoutErrors);
        }

        // Get user's addresses
        $addresses = Address::where('user_id', $userId)
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        if ($addresses->isEmpty()) {
            return redirect()->route('addresses.create')
                ->with('info', 'Please add a delivery address before checkout.');
        }

        // Get default address or first address
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        // Prepare order summary
        try {
            $orderSummary = $this->checkoutService->prepareCheckout($userId, $defaultAddress->id);
        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Error preparing checkout: '.$e->getMessage());
        }

        return view('pages.checkout', [
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'orderSummary' => $orderSummary,
            'cartItems' => $cartItems,
        ]);
    }

    /**
     * Process the order placement.
     * THE CRITICAL ENDPOINT - handles cart to order conversion.
     */
    public function placeOrder(PlaceOrderRequest $request): RedirectResponse
    {
        $userId = Auth::id();

        try {
            // Get validated data
            $addressId = $request->getAddressId();
            $customerNotes = $request->getCustomerNotes();

            // Verify address belongs to user
            $address = Address::where('id', $addressId)
                ->where('user_id', $userId)
                ->firstOrFail();

            // Get cart items and prepare checkout DTO
            $cartItems = $this->cartRepository->getUserCartItems($userId);

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Your cart is empty.');
            }

            // Convert cart items to DTOs
            $items = $cartItems->map(fn ($cartItem) => CartItemDTO::fromCartItem($cartItem))->toArray();

            // Calculate totals
            $subtotal = array_reduce(
                $items,
                fn (float $carry, CartItemDTO $item) => $carry + $item->subtotal,
                0
            );

            $taxRate = 0.0; // TODO: Get from config
            $tax = $subtotal * $taxRate;
            $shippingFee = 0.0; // TODO: Calculate based on address
            $total = $subtotal + $tax + $shippingFee;

            // Create checkout DTO
            $checkoutDTO = new CheckoutDTO(
                userId: $userId,
                addressId: $addressId,
                address: $address,
                items: $items,
                subtotal: $subtotal,
                tax: $tax,
                shippingFee: $shippingFee,
                total: $total,
                customerNotes: $customerNotes,
            );

            // Place order (atomic transaction)
            $order = $this->checkoutService->placeOrder($checkoutDTO);

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order placed successfully! Order Number: '.$order->order_number);
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Failed to place order. Please try again or contact support.');
        }
    }
}
