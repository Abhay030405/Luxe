<?php

declare(strict_types=1);

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly Category $model
    ) {}

    /**
     * Get all categories.
     */
    public function all(): Collection
    {
        return $this->model->with('parent')->orderBy('sort_order')->get();
    }

    /**
     * Get all active categories.
     */
    public function getAllActive(): Collection
    {
        return $this->model->active()->with('parent')->orderBy('sort_order')->get();
    }

    /**
     * Get all root categories (categories without parent).
     */
    public function getRootCategories(): Collection
    {
        return $this->model->root()->active()->orderBy('sort_order')->get();
    }

    /**
     * Get category by ID.
     */
    public function findById(int $id): ?Category
    {
        return $this->model->with(['parent', 'children'])->find($id);
    }

    /**
     * Get category by slug.
     */
    public function findBySlug(string $slug): ?Category
    {
        return $this->model->where('slug', $slug)->with(['parent', 'children'])->first();
    }

    /**
     * Get subcategories of a category.
     */
    public function getSubcategories(int $parentId): Collection
    {
        return $this->model->where('parent_id', $parentId)->active()->orderBy('sort_order')->get();
    }

    /**
     * Create a new category.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    /**
     * Update a category.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): Category
    {
        $category = $this->model->findOrFail($id);
        $category->update($data);

        return $category->fresh();
    }

    /**
     * Delete a category.
     */
    public function delete(int $id): bool
    {
        $category = $this->model->findOrFail($id);

        return $category->delete();
    }

    /**
     * Check if category has products.
     */
    public function hasProducts(int $id): bool
    {
        $category = $this->model->findOrFail($id);

        return $category->products()->exists();
    }
}
