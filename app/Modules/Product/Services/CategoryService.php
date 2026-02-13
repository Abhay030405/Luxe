<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\DTOs\CategoryDTO;
use App\Modules\Product\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * Get all categories.
     */
    public function getAllCategories(): Collection
    {
        $categories = $this->categoryRepository->all();

        return $categories->map(fn ($category) => CategoryDTO::fromModel($category));
    }

    /**
     * Get all active categories.
     */
    public function getActiveCategories(): Collection
    {
        $categories = $this->categoryRepository->getAllActive();

        return $categories->map(fn ($category) => CategoryDTO::fromModel($category));
    }

    /**
     * Get root categories (for navigation).
     */
    public function getRootCategories(): Collection
    {
        $categories = $this->categoryRepository->getRootCategories();

        return $categories->map(fn ($category) => CategoryDTO::fromModel($category));
    }

    /**
     * Get category by ID.
     */
    public function getCategoryById(int $id): CategoryDTO
    {
        $category = $this->categoryRepository->findById($id);

        if (! $category) {
            throw new InvalidArgumentException("Category with ID {$id} not found");
        }

        return CategoryDTO::fromModel($category);
    }

    /**
     * Get category by slug.
     */
    public function getCategoryBySlug(string $slug): CategoryDTO
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if (! $category) {
            throw new InvalidArgumentException("Category with slug '{$slug}' not found");
        }

        return CategoryDTO::fromModel($category);
    }

    /**
     * Get subcategories of a category.
     */
    public function getSubcategories(int $parentId): Collection
    {
        $subcategories = $this->categoryRepository->getSubcategories($parentId);

        return $subcategories->map(fn ($category) => CategoryDTO::fromModel($category));
    }

    /**
     * Create a new category.
     *
     * @param  array<string, mixed>  $data
     */
    public function createCategory(array $data): CategoryDTO
    {
        return DB::transaction(function () use ($data) {
            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            // Validate parent_id if provided
            if (! empty($data['parent_id'])) {
                $parent = $this->categoryRepository->findById($data['parent_id']);
                if (! $parent) {
                    throw new InvalidArgumentException('Invalid parent category');
                }
            }

            $category = $this->categoryRepository->create($data);

            return CategoryDTO::fromModel($category);
        });
    }

    /**
     * Update a category.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateCategory(int $id, array $data): CategoryDTO
    {
        return DB::transaction(function () use ($id, $data) {
            // Prevent circular parent references
            if (! empty($data['parent_id']) && $data['parent_id'] === $id) {
                throw new InvalidArgumentException('Category cannot be its own parent');
            }

            // Generate slug if name is updated
            if (! empty($data['name']) && empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $category = $this->categoryRepository->update($id, $data);

            return CategoryDTO::fromModel($category);
        });
    }

    /**
     * Delete a category.
     */
    public function deleteCategory(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            // Check if category has products
            if ($this->categoryRepository->hasProducts($id)) {
                throw new InvalidArgumentException('Cannot delete category with existing products');
            }

            // Check if category has subcategories
            $category = $this->categoryRepository->findById($id);
            if ($category && $category->hasChildren()) {
                throw new InvalidArgumentException('Cannot delete category with subcategories');
            }

            return $this->categoryRepository->delete($id);
        });
    }
}
