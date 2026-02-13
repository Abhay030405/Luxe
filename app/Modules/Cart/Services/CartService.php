<?php

declare(strict_types=1);

namespace App\Modules\Cart\Services;

use App\Modules\Cart\DTOs\CartItemDTO;
use App\Modules\Cart\DTOs\CartSummaryDTO;
use App\Modules\Cart\Repositories\Contracts\CartRepositoryInterface;
use App\Modules\Product\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CartService
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Get user's cart with all items.
     */
    public function getUserCart(int $userId): CartSummaryDTO
    {
        $cartItems = $this->cartRepository->getUserCartItems($userId);

        $items = $cartItems->map(fn ($cartItem) => CartItemDTO::fromModel($cartItem));

        return new CartSummaryDTO(
            items: $items,
            totalItems: $cartItems->count(),
            totalQuantity: $this->cartRepository->getTotalQuantity($userId),
            grandTotal: $this->cartRepository->getCartTotal($userId),
        );
    }

    /**
     * Add a product to cart.
     */
    public function addToCart(int $userId, int $productId, int $quantity = 1): CartItemDTO
    {
        // Validate product exists and is available
        $product = $this->productRepository->findById($productId);

        if (! $product) {
            throw new InvalidArgumentException('Product not found');
        }

        if ($product->status !== 'active') {
            throw new InvalidArgumentException('Product is not available');
        }

        if (! $product->isInStock()) {
            throw new InvalidArgumentException('Product is out of stock');
        }

        // Check if adding this quantity would exceed stock
        $existingCartItem = $this->cartRepository->findByUserAndProduct($userId, $productId);
        $currentQuantityInCart = $existingCartItem?->quantity ?? 0;
        $newTotalQuantity = $currentQuantityInCart + $quantity;

        if ($newTotalQuantity > $product->stock_quantity) {
            throw new InvalidArgumentException(
                "Cannot add {$quantity} items. Only {$product->stock_quantity} available in stock"
            );
        }

        // Get effective price (sale price if available, otherwise regular price)
        $price = $product->getEffectivePrice();

        // Add or update cart item
        $cartItem = $this->cartRepository->addOrUpdate($userId, $productId, $quantity, $price);

        return CartItemDTO::fromModel($cartItem);
    }

    /**
     * Update cart item quantity.
     */
    public function updateCartItemQuantity(int $userId, int $cartItemId, int $quantity): CartItemDTO
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException('Quantity must be at least 1');
        }

        $cartItem = $this->cartRepository->findById($cartItemId);

        if (! $cartItem) {
            throw new InvalidArgumentException('Cart item not found');
        }

        // Ensure user owns this cart item
        if ($cartItem->user_id !== $userId) {
            throw new InvalidArgumentException('Unauthorized access to cart item');
        }

        // Check stock availability
        $product = $cartItem->product;

        if ($quantity > $product->stock_quantity) {
            throw new InvalidArgumentException(
                "Cannot set quantity to {$quantity}. Only {$product->stock_quantity} available in stock"
            );
        }

        if (! $product->isInStock()) {
            throw new InvalidArgumentException('Product is out of stock');
        }

        // Update quantity
        $this->cartRepository->updateQuantity($cartItemId, $quantity);

        // Refresh cart item
        $updatedCartItem = $this->cartRepository->findById($cartItemId);

        return CartItemDTO::fromModel($updatedCartItem);
    }

    /**
     * Remove item from cart.
     */
    public function removeFromCart(int $userId, int $cartItemId): bool
    {
        $cartItem = $this->cartRepository->findById($cartItemId);

        if (! $cartItem) {
            throw new InvalidArgumentException('Cart item not found');
        }

        // Ensure user owns this cart item
        if ($cartItem->user_id !== $userId) {
            throw new InvalidArgumentException('Unauthorized access to cart item');
        }

        return $this->cartRepository->remove($cartItemId);
    }

    /**
     * Clear entire cart for a user.
     */
    public function clearCart(int $userId): bool
    {
        return $this->cartRepository->clearUserCart($userId);
    }

    /**
     * Get cart item count for a user.
     */
    public function getCartItemCount(int $userId): int
    {
        return $this->cartRepository->getCartItemCount($userId);
    }

    /**
     * Get total quantity in cart for a user.
     */
    public function getTotalQuantity(int $userId): int
    {
        return $this->cartRepository->getTotalQuantity($userId);
    }

    /**
     * Validate cart items before checkout.
     * Returns array of validation issues if any.
     *
     * @return array<string, mixed>
     */
    public function validateCart(int $userId): array
    {
        $cartItems = $this->cartRepository->getUserCartItems($userId);
        $issues = [];

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            // Check if product still exists and is active
            if (! $product || $product->status !== 'active') {
                $issues[] = [
                    'cart_item_id' => $cartItem->id,
                    'product_name' => $cartItem->product?->name ?? 'Unknown',
                    'issue' => 'Product is no longer available',
                ];

                continue;
            }

            // Check stock availability
            if ($cartItem->quantity > $product->stock_quantity) {
                $issues[] = [
                    'cart_item_id' => $cartItem->id,
                    'product_name' => $product->name,
                    'issue' => "Requested quantity ({$cartItem->quantity}) exceeds available stock ({$product->stock_quantity})",
                ];
            }

            // Check if product is still in stock
            if (! $product->isInStock()) {
                $issues[] = [
                    'cart_item_id' => $cartItem->id,
                    'product_name' => $product->name,
                    'issue' => 'Product is out of stock',
                ];
            }
        }

        return $issues;
    }

    /**
     * Sync cart items with current product prices.
     * Useful before checkout to ensure prices are current.
     */
    public function syncCartPrices(int $userId): void
    {
        $cartItems = $this->cartRepository->getUserCartItems($userId);

        DB::transaction(function () use ($cartItems) {
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                if ($product) {
                    $currentPrice = $product->getEffectivePrice();

                    if ((float) $cartItem->price_at_time !== $currentPrice) {
                        $cartItem->price_at_time = $currentPrice;
                        $cartItem->save();
                    }
                }
            }
        });
    }
}
