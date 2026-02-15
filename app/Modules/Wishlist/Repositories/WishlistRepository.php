<?php

declare(strict_types=1);

namespace App\Modules\Wishlist\Repositories;

use App\Modules\Wishlist\Models\WishlistItem;
use Illuminate\Database\Eloquent\Collection;

class WishlistRepository
{
    /**
     * Get all wishlist items for a user with product details.
     */
    public function getUserWishlist(int $userId): Collection
    {
        return WishlistItem::query()
            ->with(['product.category', 'product.images'])
            ->forUser($userId)
            ->latest()
            ->get();
    }

    /**
     * Add a product to the wishlist.
     */
    public function addToWishlist(int $userId, int $productId): WishlistItem
    {
        return WishlistItem::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    /**
     * Remove a product from the wishlist.
     */
    public function removeFromWishlist(int $userId, int $productId): bool
    {
        return WishlistItem::query()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete() > 0;
    }

    /**
     * Check if a product is in the user's wishlist.
     */
    public function isInWishlist(int $userId, int $productId): bool
    {
        return WishlistItem::query()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
    }

    /**
     * Get wishlist count for a user.
     */
    public function getWishlistCount(int $userId): int
    {
        return WishlistItem::query()
            ->forUser($userId)
            ->count();
    }

    /**
     * Clear entire wishlist for a user.
     */
    public function clearWishlist(int $userId): bool
    {
        return WishlistItem::query()
            ->forUser($userId)
            ->delete() > 0;
    }

    /**
     * Get wishlist product IDs for a user.
     */
    public function getWishlistProductIds(int $userId): array
    {
        return WishlistItem::query()
            ->forUser($userId)
            ->pluck('product_id')
            ->toArray();
    }
}
