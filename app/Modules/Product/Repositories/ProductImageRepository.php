<?php

declare(strict_types=1);

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\ProductImage;
use App\Modules\Product\Repositories\Contracts\ProductImageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductImageRepository implements ProductImageRepositoryInterface
{
    public function __construct(
        private readonly ProductImage $model
    ) {}

    /**
     * Get all images for a product.
     */
    public function getByProduct(int $productId): Collection
    {
        return $this->model->where('product_id', $productId)->orderBy('sort_order')->get();
    }

    /**
     * Get primary image for a product.
     */
    public function getPrimaryImage(int $productId): ?ProductImage
    {
        return $this->model->where('product_id', $productId)->where('is_primary', true)->first();
    }

    /**
     * Create a new product image.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): ProductImage
    {
        return $this->model->create($data);
    }

    /**
     * Update a product image.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): ProductImage
    {
        $image = $this->model->findOrFail($id);
        $image->update($data);

        return $image->fresh();
    }

    /**
     * Delete a product image.
     */
    public function delete(int $id): bool
    {
        $image = $this->model->findOrFail($id);

        return $image->delete();
    }

    /**
     * Set image as primary.
     */
    public function setPrimary(int $productId, int $imageId): bool
    {
        // Remove primary from all images for this product
        $this->model->where('product_id', $productId)->update(['is_primary' => false]);

        // Set the specified image as primary
        $image = $this->model->findOrFail($imageId);

        return $image->update(['is_primary' => true]);
    }

    /**
     * Delete all images for a product.
     */
    public function deleteByProduct(int $productId): bool
    {
        return $this->model->where('product_id', $productId)->delete() > 0;
    }
}
