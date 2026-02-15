<?php

declare(strict_types=1);

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Services\CategoryService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Wishlist\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly CategoryService $categoryService,
        private readonly WishlistService $wishlistService
    ) {}

    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Handle composite sort keys (e.g., price_asc, price_desc)
        if ($sortBy === 'price_asc') {
            $sortBy = 'price';
            $sortOrder = 'asc';
        } elseif ($sortBy === 'price_desc') {
            $sortBy = 'price';
            $sortOrder = 'desc';
        } elseif ($sortBy === 'name') {
            $sortOrder = 'asc';
        }

        $filters = [
            'category_id' => $request->input('category_id'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'is_featured' => $request->boolean('is_featured'),
            'in_stock' => $request->boolean('in_stock', true),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ];

        // Temporary debug - will remove after testing
        logger('Product filters received:', $filters);

        $products = $this->productService->getPaginatedProducts($filters, 12);
        $categories = $this->categoryService->getRootCategories();

        // Pass original request params back to view to keep filter state
        $filters['sort_by'] = $request->input('sort_by', 'created_at');

        return view('pages.product.index', compact('products', 'categories', 'filters'));
    }

    /**
     * Display the specified product.
     */
    public function show(string $slug): View
    {
        try {
            $product = $this->productService->getProductBySlug($slug);
            $relatedProducts = $this->productService->getProductsByCategory($product->categoryId, 4);

            // Check if product is in wishlist for authenticated users
            $isInWishlist = false;
            if (auth()->check()) {
                $isInWishlist = $this->wishlistService->isInWishlist(auth()->id(), $product->id);
            }

            return view('pages.product.show', compact('product', 'relatedProducts', 'isInWishlist'));
        } catch (InvalidArgumentException $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Display products by category.
     */
    public function category(string $slug): View
    {
        try {
            $category = $this->categoryService->getCategoryBySlug($slug);
            $products = $this->productService->getProductsByCategory($category->id, 12);
            $subcategories = $this->categoryService->getSubcategories($category->id);

            return view('pages.product.category', compact('category', 'products', 'subcategories'));
        } catch (InvalidArgumentException $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Search products.
     */
    public function search(Request $request): View
    {
        $keyword = $request->input('q', '');
        $products = $this->productService->searchProducts($keyword, 12);
        $categories = $this->categoryService->getRootCategories();

        return view('pages.product.search', compact('products', 'keyword', 'categories'));
    }
}
