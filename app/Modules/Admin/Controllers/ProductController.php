<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Requests\ProductImageRequest;
use App\Modules\Product\Requests\StoreProductRequest;
use App\Modules\Product\Requests\UpdateProductRequest;
use App\Modules\Product\Services\CategoryService;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use InvalidArgumentException;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly CategoryService $categoryService
    ) {}

    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $filters = [
            'category_id' => $request->input('category_id'),
            'status' => $request->input('status'),
            'is_featured' => $request->boolean('is_featured'),
        ];

        $products = $this->productService->getPaginatedProducts($filters, 20);
        $categories = $this->categoryService->getActiveCategories();

        return view('pages.admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = $this->categoryService->getActiveCategories();

        return view('pages.admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            $product = $this->productService->createProduct($request->validated());

            return redirect()
                ->route('admin.products.edit', $product->id)
                ->with('success', 'Product created successfully. Now add images.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(int $id): View
    {
        $product = $this->productService->getProductById($id);
        $categories = $this->categoryService->getActiveCategories();

        return view('pages.admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        try {
            $this->productService->updateProduct($id, $request->validated());

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->productService->deleteProduct($id);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Upload product image.
     */
    public function uploadImage(ProductImageRequest $request, int $id): RedirectResponse
    {
        try {
            $image = $request->file('image');
            $path = $image->store('products', 'public');

            $imageData = [
                'image_path' => $path,
                'alt_text' => $request->input('alt_text'),
                'is_primary' => $request->boolean('is_primary'),
                'sort_order' => $request->integer('sort_order', 0),
            ];

            $this->productService->addProductImage($id, $imageData);

            return redirect()
                ->back()
                ->with('success', 'Image uploaded successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Delete product image.
     */
    public function deleteImage(int $productId, int $imageId): RedirectResponse
    {
        try {
            $this->productService->deleteProductImage($imageId);

            return redirect()
                ->back()
                ->with('success', 'Image deleted successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
