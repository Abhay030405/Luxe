<?php

declare(strict_types=1);

namespace App\Modules\Cart\DTOs;

use App\Modules\Cart\Models\CartItem;

readonly class CartItemDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $productId,
        public string $productName,
        public string $productSlug,
        public int $quantity,
        public float $priceAtTime,
        public float $subtotal,
        public int $availableStock,
        public ?string $primaryImageUrl = null,
        public ?string $categoryName = null,
        public bool $isInStock = true,
        public ?string $createdAt = null,
    ) {}

    public static function fromModel(CartItem $cartItem): self
    {
        $product = $cartItem->product;

        return new self(
            id: $cartItem->id,
            userId: $cartItem->user_id,
            productId: $cartItem->product_id,
            productName: $product->name,
            productSlug: $product->slug,
            quantity: $cartItem->quantity,
            priceAtTime: (float) $cartItem->price_at_time,
            subtotal: $cartItem->subtotal,
            availableStock: $product->stock_quantity,
            primaryImageUrl: $product->primaryImage?->getImageUrl(),
            categoryName: $product->category?->name,
            isInStock: $product->isInStock(),
            createdAt: $cartItem->created_at?->toDateTimeString(),
        );
    }

    /**
     * Convert DTO to array for responses.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'product_slug' => $this->productSlug,
            'quantity' => $this->quantity,
            'price_at_time' => $this->priceAtTime,
            'subtotal' => $this->subtotal,
            'available_stock' => $this->availableStock,
            'primary_image_url' => $this->primaryImageUrl,
            'category_name' => $this->categoryName,
            'is_in_stock' => $this->isInStock,
            'created_at' => $this->createdAt,
        ];
    }
}
