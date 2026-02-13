<?php

declare(strict_types=1);

namespace App\Modules\Cart\Repositories;

use App\Modules\Cart\Models\CartItem;
use App\Modules\Cart\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(
        private readonly CartItem $model
    ) {}

    /**
     * Get all cart items for a user.
     */
    public function getUserCartItems(int $userId): Collection
    {
        return $this->model
            ->with(['product.images', 'product.category'])
            ->forUser($userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find a cart item by user and product.
     */
    public function findByUserAndProduct(int $userId, int $productId): ?CartItem
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * Add a product to cart or update quantity if exists.
     */
    public function addOrUpdate(int $userId, int $productId, int $quantity, float $price): CartItem
    {
        $cartItem = $this->findByUserAndProduct($userId, $productId);

        if ($cartItem) {
            // Update existing cart item
            $cartItem->quantity += $quantity;
            $cartItem->price_at_time = $price; // Update price to latest
            $cartItem->save();

            return $cartItem->load(['product.images', 'product.category']);
        }

        // Create new cart item
        return $this->model->create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price_at_time' => $price,
        ])->load(['product.images', 'product.category']);
    }

    /**
     * Update cart item quantity.
     */
    public function updateQuantity(int $cartItemId, int $quantity): bool
    {
        $cartItem = $this->findById($cartItemId);

        if (! $cartItem) {
            return false;
        }

        $cartItem->quantity = $quantity;

        return $cartItem->save();
    }

    /**
     * Remove a cart item.
     */
    public function remove(int $cartItemId): bool
    {
        $cartItem = $this->findById($cartItemId);

        if (! $cartItem) {
            return false;
        }

        return $cartItem->delete();
    }

    /**
     * Clear all cart items for a user.
     */
    public function clearUserCart(int $userId): bool
    {
        return $this->model->forUser($userId)->delete() > 0;
    }

    /**
     * Get cart item by ID.
     */
    public function findById(int $id): ?CartItem
    {
        return $this->model
            ->with(['product.images', 'product.category'])
            ->find($id);
    }

    /**
     * Get cart total for a user.
     */
    public function getCartTotal(int $userId): float
    {
        return (float) $this->model
            ->forUser($userId)
            ->get()
            ->sum(fn ($item) => $item->subtotal);
    }

    /**
     * Get cart item count for a user.
     */
    public function getCartItemCount(int $userId): int
    {
        return $this->model->forUser($userId)->count();
    }

    /**
     * Get total quantity of items in cart for a user.
     */
    public function getTotalQuantity(int $userId): int
    {
        return (int) $this->model->forUser($userId)->sum('quantity');
    }
}
