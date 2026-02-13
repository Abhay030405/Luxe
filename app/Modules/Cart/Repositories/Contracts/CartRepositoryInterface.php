<?php

declare(strict_types=1);

namespace App\Modules\Cart\Repositories\Contracts;

use App\Modules\Cart\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;

interface CartRepositoryInterface
{
    /**
     * Get all cart items for a user.
     */
    public function getUserCartItems(int $userId): Collection;

    /**
     * Find a cart item by user and product.
     */
    public function findByUserAndProduct(int $userId, int $productId): ?CartItem;

    /**
     * Add a product to cart or update quantity if exists.
     */
    public function addOrUpdate(int $userId, int $productId, int $quantity, float $price): CartItem;

    /**
     * Update cart item quantity.
     */
    public function updateQuantity(int $cartItemId, int $quantity): bool;

    /**
     * Remove a cart item.
     */
    public function remove(int $cartItemId): bool;

    /**
     * Clear all cart items for a user.
     */
    public function clearUserCart(int $userId): bool;

    /**
     * Get cart item by ID.
     */
    public function findById(int $id): ?CartItem;

    /**
     * Get cart total for a user.
     */
    public function getCartTotal(int $userId): float;

    /**
     * Get cart item count for a user.
     */
    public function getCartItemCount(int $userId): int;

    /**
     * Get total quantity of items in cart for a user.
     */
    public function getTotalQuantity(int $userId): int;
}
