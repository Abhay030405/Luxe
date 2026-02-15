<?php

declare(strict_types=1);

namespace App\Modules\Product\DTOs;

use App\Modules\Product\Models\Product;
use Illuminate\Support\Collection;

readonly class ProductDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public int $categoryId,
        public string $categoryName,
        public ?string $description = null,
        public ?string $shortDescription = null,
        public float $price = 0.0,
        public ?float $salePrice = null,
        public int $stockQuantity = 0,
        public ?string $sku = null,
        public string $status = 'active',
        public bool $isFeatured = false,
        public ?float $weight = null,
        public ?array $metaData = null,
        public ?Collection $images = null,
        public ?string $primaryImageUrl = null,
        public bool $isOnSale = false,
        public ?int $discountPercentage = null,
        public float $effectivePrice = 0.0,
        public bool $isInStock = false,
        public ?string $createdAt = null,
    ) {}

    public static function fromModel(Product $product): self
    {
        return new self(
            id: $product->id,
            name: $product->name,
            slug: $product->slug,
            categoryId: (int) $product->category_id,
            categoryName: $product->category?->name ?? 'Unknown',
            description: $product->description,
            shortDescription: $product->short_description,
            price: (float) $product->price,
            salePrice: $product->sale_price ? (float) $product->sale_price : null,
            stockQuantity: $product->stock_quantity,
            sku: $product->sku,
            status: $product->status,
            isFeatured: $product->is_featured,
            weight: $product->weight ? (float) $product->weight : null,
            metaData: $product->meta_data,
            images: $product->images?->map(fn ($image) => [
                'id' => $image->id,
                'url' => $image->getImageUrl(),
                'alt_text' => $image->alt_text,
                'is_primary' => $image->is_primary,
            ]),
            primaryImageUrl: $product->primaryImage?->getImageUrl(),
            isOnSale: $product->isOnSale(),
            discountPercentage: $product->getDiscountPercentage(),
            effectivePrice: $product->getEffectivePrice(),
            isInStock: $product->isInStock(),
            createdAt: $product->created_at?->toDateTimeString(),
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
            'name' => $this->name,
            'slug' => $this->slug,
            'category_id' => $this->categoryId,
            'category_name' => $this->categoryName,
            'description' => $this->description,
            'short_description' => $this->shortDescription,
            'price' => $this->price,
            'sale_price' => $this->salePrice,
            'stock_quantity' => $this->stockQuantity,
            'sku' => $this->sku,
            'status' => $this->status,
            'is_featured' => $this->isFeatured,
            'weight' => $this->weight,
            'meta_data' => $this->metaData,
            'images' => $this->images?->toArray(),
            'primary_image_url' => $this->primaryImageUrl,
            'is_on_sale' => $this->isOnSale,
            'discount_percentage' => $this->discountPercentage,
            'effective_price' => $this->effectivePrice,
            'is_in_stock' => $this->isInStock,
            'created_at' => $this->createdAt,
        ];
    }
}
