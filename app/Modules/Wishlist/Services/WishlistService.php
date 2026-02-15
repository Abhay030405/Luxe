<?php

declare(strict_types=1);

namespace App\Modules\Wishlist\Services;

use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Wishlist\DTOs\WishlistItemDTO;
use App\Modules\Wishlist\Repositories\WishlistRepository;
use Illuminate\Support\Collection;

class WishlistService
{
    public function __construct(
        private readonly WishlistRepository $wishlistRepository,
        private readonly ProductRepository $productRepository,
    ) {}

    /**
     * Get user's wishlist with product details.
     *
     * @return Collection<int, WishlistItemDTO>
     */
    public function getUserWishlist(int $userId): Collection
    {
        $wishlistItems = $this->wishlistRepository->getUserWishlist($userId);

        return $wishlistItems->map(fn ($item) => WishlistItemDTO::fromModel($item));
    }

    /**
     * Add a product to the wishlist.
     */
    public function addToWishlist(int $userId, int $productId): array
    {
        // Check if product exists
        $product = $this->productRepository->findById($productId);

        if (! $product) {
            return [
                'success' => false,
                'message' => 'Product not found.',
            ];
        }

        // Check if already in wishlist
        if ($this->wishlistRepository->isInWishlist($userId, $productId)) {
            return [
                'success' => false,
                'message' => 'Product is already in your wishlist.',
            ];
        }

        $this->wishlistRepository->addToWishlist($userId, $productId);

        return [
            'success' => true,
            'message' => 'Product added to wishlist successfully.',
            'count' => $this->getWishlistCount($userId),
        ];
    }

    /**
     * Remove a product from the wishlist.
     */
    public function removeFromWishlist(int $userId, int $productId): array
    {
        $removed = $this->wishlistRepository->removeFromWishlist($userId, $productId);

        return [
            'success' => $removed,
            'message' => $removed ? 'Product removed from wishlist.' : 'Product not found in wishlist.',
            'count' => $this->getWishlistCount($userId),
        ];
    }

    /**
     * Toggle wishlist status (add if not present, remove if present).
     */
    public function toggleWishlist(int $userId, int $productId): array
    {
        if ($this->wishlistRepository->isInWishlist($userId, $productId)) {
            return $this->removeFromWishlist($userId, $productId);
        }

        return $this->addToWishlist($userId, $productId);
    }

    /**
     * Check if a product is in the user's wishlist.
     */
    public function isInWishlist(int $userId, int $productId): bool
    {
        return $this->wishlistRepository->isInWishlist($userId, $productId);
    }

    /**
     * Get wishlist count for a user.
     */
    public function getWishlistCount(int $userId): int
    {
        return $this->wishlistRepository->getWishlistCount($userId);
    }

    /**
     * Clear entire wishlist for a user.
     */
    public function clearWishlist(int $userId): bool
    {
        return $this->wishlistRepository->clearWishlist($userId);
    }

    /**
     * Get wishlist product IDs for a user.
     */
    public function getWishlistProductIds(int $userId): array
    {
        return $this->wishlistRepository->getWishlistProductIds($userId);
    }
}
