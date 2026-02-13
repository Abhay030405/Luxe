<?php

declare(strict_types=1);

namespace App\Modules\Product\Repositories\Contracts;

use App\Modules\Product\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    /**
     * Get all categories.
     */
    public function all(): Collection;

    /**
     * Get all active categories.
     */
    public function getAllActive(): Collection;

    /**
     * Get all root categories (categories without parent).
     */
    public function getRootCategories(): Collection;

    /**
     * Get category by ID.
     */
    public function findById(int $id): ?Category;

    /**
     * Get category by slug.
     */
    public function findBySlug(string $slug): ?Category;

    /**
     * Get subcategories of a category.
     */
    public function getSubcategories(int $parentId): Collection;

    /**
     * Create a new category.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Category;

    /**
     * Update a category.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): Category;

    /**
     * Delete a category.
     */
    public function delete(int $id): bool;

    /**
     * Check if category has products.
     */
    public function hasProducts(int $id): bool;
}
