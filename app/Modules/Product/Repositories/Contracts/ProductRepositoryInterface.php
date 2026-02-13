<?php

declare(strict_types=1);

namespace App\Modules\Product\Repositories\Contracts;

use App\Modules\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * Get all products.
     */
    public function all(): Collection;

    /**
     * Get paginated products with filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get product by ID.
     */
    public function findById(int $id): ?Product;

    /**
     * Get product by slug.
     */
    public function findBySlug(string $slug): ?Product;

    /**
     * Get products by category.
     */
    public function getByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get featured products.
     */
    public function getFeatured(int $limit = 8): Collection;

    /**
     * Get latest products.
     */
    public function getLatest(int $limit = 8): Collection;

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product;

    /**
     * Update a product.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): Product;

    /**
     * Delete a product.
     */
    public function delete(int $id): bool;

    /**
     * Update stock quantity.
     */
    public function updateStock(int $id, int $quantity): bool;

    /**
     * Search products by keyword.
     */
    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator;
}
