<?php

declare(strict_types=1);

namespace App\Modules\Wishlist\DTOs;

use App\Modules\Product\DTOs\ProductDTO;

readonly class WishlistItemDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $productId,
        public ProductDTO $product,
        public string $addedAt,
    ) {}

    public static function fromModel($wishlistItem): self
    {
        return new self(
            id: $wishlistItem->id,
            userId: $wishlistItem->user_id,
            productId: $wishlistItem->product_id,
            product: ProductDTO::fromModel($wishlistItem->product),
            addedAt: $wishlistItem->created_at->format('M d, Y'),
        );
    }
}
