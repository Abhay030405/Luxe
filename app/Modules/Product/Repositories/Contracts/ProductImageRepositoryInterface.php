<?php

declare(strict_types=1);

namespace App\Modules\Product\Repositories\Contracts;

use App\Modules\Product\Models\ProductImage;
use Illuminate\Database\Eloquent\Collection;

interface ProductImageRepositoryInterface
{
    /**
     * Get all images for a product.
     */
    public function getByProduct(int $productId): Collection;

    /**
     * Get primary image for a product.
     */
    public function getPrimaryImage(int $productId): ?ProductImage;

    /**
     * Create a new product image.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): ProductImage;

    /**
     * Update a product image.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): ProductImage;

    /**
     * Delete a product image.
     */
    public function delete(int $id): bool;

    /**
     * Set image as primary.
     */
    public function setPrimary(int $productId, int $imageId): bool;

    /**
     * Delete all images for a product.
     */
    public function deleteByProduct(int $productId): bool;
}
