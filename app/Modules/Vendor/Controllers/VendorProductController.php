<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Requests\ProductImageRequest;
use App\Modules\Product\Requests\StoreProductRequest;
use App\Modules\Product\Requests\UpdateProductRequest;
use App\Modules\Product\Services\CategoryService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class VendorProductController extends Controller
{
    public function __construct(
        private readonly VendorService $vendorService,
        private readonly ProductService $productService,
        private readonly CategoryService $categoryService
    ) {
        $this->middleware(['auth', 'vendor']);
    }

    /**
     * Display vendor's products.
     */
    public function index(Request $request): View
    {
        $vendor = $this->vendorService->getVendorByUserId(auth()->id());

        if (! $vendor || $vendor->status !== 'approved') {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'You must have an approved vendor account to manage products.');
        }

        // Get vendor's products with filters
        $filters = [
            'vendor_id' => $vendor->id,
            'status' => $request->input('status'),
            'search' => $request->input('search'),
        ];

        $products = $this->productService->getPaginatedProducts($filters, 15);

        return view('pages.vendor.products.index', compact('products', 'vendor'));
    }

    /**
     * Show form for creating a new product.
     */
    public function create(): View
    {
        $vendor = $this->vendorService->getVendorByUserId(auth()->id());

        if (! $vendor || $vendor->status !== 'approved') {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'You must have an approved vendor account to add products.');
        }

        $categories = $this->categoryService->getAllCategories();

        return view('pages.vendor.products.create', compact('vendor', 'categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $vendor = $this->vendorService->getVendorByUserId(auth()->id());

        if (! $vendor || $vendor->status !== 'approved') {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'You must have an approved vendor account to add products.');
        }

        try {
            $data = $request->validated();
            $data['vendor_id'] = $vendor->id;

            $product = $this->productService->createProduct($data);

            return redirect()
                ->route('vendor.products.edit', $product->id)
                ->with('success', 'Product created successfully. Now add images.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show form for editing a product.
     */
    public function edit(int $id): View
    {
        $vendor = $this->vendorService->getVendorByUserId(auth()->id());
        $product = $this->productService->getProductById($id);

        // Ensure vendor can only edit their own products
        if ($product->vendorId !== $vendor->id) {
            abort(403, 'Unauthorized action.');
        }

        $categories = $this->categoryService->getAllCategories();

        return view('pages.vendor.products.edit', compact('product', 'vendor', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        $vendor = $this->vendorService->getVendorByUserId(auth()->id());
        $product = $this->productService->getProductById($id);

        // Ensure vendor can only update their own products
        if ($product->vendorId !== $vendor->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $this->productService->updateProduct($id, $request->validated());

            return redirect()
                ->route('vendor.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Upload product image.
     */
    public function uploadImage(ProductImageRequest $request, int $id): RedirectResponse
    {
        $vendor = $this->vendorService->getVendorByUserId(auth()->id());
        $product = $this->productService->getProductById($id);

        // Ensure vendor can only upload images to their own products
        if ($product->vendorId !== $vendor->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $image = $request->file('image');
            $path = $image->store('products', 'public');

            $imageData = [
                'image_path' => $path,
                'alt_text' => $request->input('alt_text', ''),
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
        $vendor = $this->vendorService->getVendorByUserId(auth()->id());
        $product = $this->productService->getProductById($productId);

        // Ensure vendor can only delete images from their own products
        if ($product->vendorId !== $vendor->id) {
            abort(403, 'Unauthorized action.');
        }

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

    /**
     * Delete a product.
     */
    public function destroy(int $id): RedirectResponse
    {
        $vendor = $this->vendorService->getVendorByUserId(auth()->id());
        $product = $this->productService->getProductById($id);

        // Ensure vendor can only delete their own products
        if ($product->vendorId !== $vendor->id) {
            abort(403, 'Unauthorized action.');
        }

        $this->productService->deleteProduct($id);

        return redirect()
            ->route('vendor.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
