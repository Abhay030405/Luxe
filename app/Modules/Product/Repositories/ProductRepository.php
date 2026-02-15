<?php

declare(strict_types=1);

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly Product $model
    ) {}

    /**
     * Get all products.
     */
    public function all(): Collection
    {
        return $this->model->with(['category', 'images'])->get();
    }

    /**
     * Get paginated products with filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['category', 'images']);

        // Temporary debug - will remove after testing
        logger('Repository applying filters:', $filters);

        // Filter by category
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
            logger('Applying category filter:', ['category_id' => $filters['category_id']]);
        }

        // Filter by status
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        } else {
            $query->active(); // Default to active products
        }

        // Filter by price range
        if (! empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
            logger('Applying min_price filter:', ['min_price' => $filters['min_price']]);
        }

        if (! empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
            logger('Applying max_price filter:', ['max_price' => $filters['max_price']]);
        }

        // Filter by featured
        if (! empty($filters['is_featured'])) {
            $query->featured();
            logger('Applying featured filter');
        }

        // Filter by in stock
        if (! empty($filters['in_stock'])) {
            $query->inStock();
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $results = $query->paginate($perPage);
        logger('Query result count:', ['count' => $results->count(), 'total' => $results->total()]);

        return $results;
    }

    /**
     * Get product by ID.
     */
    public function findById(int $id): ?Product
    {
        return $this->model->with(['category', 'images'])->find($id);
    }

    /**
     * Get product by slug.
     */
    public function findBySlug(string $slug): ?Product
    {
        return $this->model->where('slug', $slug)->with(['category', 'images'])->first();
    }

    /**
     * Get products by category.
     */
    public function getByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['category', 'images'])
            ->where('category_id', $categoryId)
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get featured products.
     */
    public function getFeatured(int $limit = 8): Collection
    {
        return $this->model
            ->with(['category', 'images'])
            ->featured()
            ->active()
            ->inStock()
            ->limit($limit)
            ->get();
    }

    /**
     * Get latest products.
     */
    public function getLatest(int $limit = 8): Collection
    {
        return $this->model
            ->with(['category', 'images'])
            ->active()
            ->inStock()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    /**
     * Update a product.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): Product
    {
        $product = $this->model->findOrFail($id);
        $product->update($data);

        return $product->fresh(['category', 'images']);
    }

    /**
     * Delete a product.
     */
    public function delete(int $id): bool
    {
        $product = $this->model->findOrFail($id);

        return $product->delete();
    }

    /**
     * Update stock quantity.
     */
    public function updateStock(int $id, int $quantity): bool
    {
        $product = $this->model->findOrFail($id);

        return $product->update(['stock_quantity' => $quantity]);
    }

    /**
     * Search products by keyword.
     */
    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['category', 'images'])
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('short_description', 'like', "%{$keyword}%");
            })
            ->active()
            ->paginate($perPage);
    }
}
