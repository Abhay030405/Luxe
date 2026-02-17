<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\DTOs\ProductDTO;
use App\Modules\Product\Models\ProductImage;
use App\Modules\Product\Repositories\Contracts\ProductImageRepositoryInterface;
use App\Modules\Product\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductImageRepositoryInterface $imageRepository
    ) {}

    /**
     * Get paginated products with filters.
     *
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<ProductDTO>
     */
    public function getPaginatedProducts(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $products = $this->productRepository->getPaginated($filters, $perPage);

        // Transform each item to DTO
        $products->getCollection()->transform(fn ($product) => ProductDTO::fromModel($product));

        return $products;
    }

    /**
     * Get product by ID.
     */
    public function getProductById(int $id): ProductDTO
    {
        $product = $this->productRepository->findById($id);

        if (! $product) {
            throw new InvalidArgumentException("Product with ID {$id} not found");
        }

        return ProductDTO::fromModel($product);
    }

    /**
     * Get product by slug.
     */
    public function getProductBySlug(string $slug): ProductDTO
    {
        $product = $this->productRepository->findBySlug($slug);

        if (! $product) {
            throw new InvalidArgumentException("Product with slug '{$slug}' not found");
        }

        return ProductDTO::fromModel($product);
    }

    /**
     * Get products by category.
     *
     * @return LengthAwarePaginator<ProductDTO>
     */
    public function getProductsByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator
    {
        $products = $this->productRepository->getByCategory($categoryId, $perPage);

        $products->getCollection()->transform(fn ($product) => ProductDTO::fromModel($product));

        return $products;
    }

    /**
     * Get featured products.
     */
    public function getFeaturedProducts(int $limit = 8): Collection
    {
        $products = $this->productRepository->getFeatured($limit);

        return $products->map(fn ($product) => ProductDTO::fromModel($product));
    }

    /**
     * Get latest products.
     */
    public function getLatestProducts(int $limit = 8): Collection
    {
        $products = $this->productRepository->getLatest($limit);

        return $products->map(fn ($product) => ProductDTO::fromModel($product));
    }

    /**
     * Search products.
     *
     * @return LengthAwarePaginator<ProductDTO>
     */
    public function searchProducts(string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        $products = $this->productRepository->search($keyword, $perPage);

        $products->getCollection()->transform(fn ($product) => ProductDTO::fromModel($product));

        return $products;
    }

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     */
    public function createProduct(array $data): ProductDTO
    {
        return DB::transaction(function () use ($data) {
            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            // Set default status if not provided
            $data['status'] = $data['status'] ?? 'active';

            $product = $this->productRepository->create($data);

            return ProductDTO::fromModel($product);
        });
    }

    /**
     * Update a product.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateProduct(int $id, array $data): ProductDTO
    {
        return DB::transaction(function () use ($id, $data) {
            // Generate slug if name is updated
            if (! empty($data['name']) && empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            // Validate stock quantity
            if (isset($data['stock_quantity']) && $data['stock_quantity'] < 0) {
                throw new InvalidArgumentException('Stock quantity cannot be negative');
            }

            // Auto-update status based on stock
            if (isset($data['stock_quantity']) && $data['stock_quantity'] === 0) {
                $data['status'] = 'out_of_stock';
            }

            $product = $this->productRepository->update($id, $data);

            return ProductDTO::fromModel($product);
        });
    }

    /**
     * Delete a product.
     */
    public function deleteProduct(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            // Get product to delete its images
            $product = $this->productRepository->findById($id);

            if (! $product) {
                throw new InvalidArgumentException("Product with ID {$id} not found");
            }

            // Delete all images from storage
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }

            // Delete product (images will be deleted by cascade)
            return $this->productRepository->delete($id);
        });
    }

    /**
     * Update product stock.
     */
    public function updateStock(int $id, int $quantity): bool
    {
        return DB::transaction(function () use ($id, $quantity) {
            if ($quantity < 0) {
                throw new InvalidArgumentException('Stock quantity cannot be negative');
            }

            return $this->productRepository->updateStock($id, $quantity);
        });
    }

    /**
     * Add image to product.
     *
     * @param  array<string, mixed>  $imageData
     */
    public function addProductImage(int $productId, array $imageData): void
    {
        DB::transaction(function () use ($productId, $imageData) {
            // If this is the first image or marked as primary, set it as primary
            $existingImages = $this->imageRepository->getByProduct($productId);

            if ($existingImages->isEmpty() || ($imageData['is_primary'] ?? false)) {
                // Remove primary from existing images
                $this->imageRepository->getByProduct($productId)->each(function ($image) {
                    $this->imageRepository->update($image->id, ['is_primary' => false]);
                });

                $imageData['is_primary'] = true;
            }

            $imageData['product_id'] = $productId;
            $this->imageRepository->create($imageData);
        });
    }

    /**
     * Delete product image.
     */
    public function deleteProductImage(int $imageId): bool
    {
        return DB::transaction(function () use ($imageId) {
            $image = ProductImage::find($imageId);

            if (! $image) {
                throw new InvalidArgumentException("Image with ID {$imageId} not found");
            }

            // Delete from storage
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            return $this->imageRepository->delete($imageId);
        });
    }
}
