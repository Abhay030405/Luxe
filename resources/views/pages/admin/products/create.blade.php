@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
            <p class="mt-1 text-sm text-gray-600">Create a new product for your store</p>
        </div>
        <x-link href="/admin/products">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Products
        </x-link>
    </div>

    <form class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <x-card title="Basic Information">
                <div class="space-y-4">
                    <x-input label="Product Name" name="name" placeholder="Enter product name" required />
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                        <textarea rows="6" name="description" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600" placeholder="Enter product description"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-input label="SKU" name="sku" placeholder="PRD-001" required />
                        <x-input label="Barcode" name="barcode" placeholder="123456789012" />
                    </div>
                </div>
            </x-card>

            <!-- Pricing -->
            <x-card title="Pricing">
                <div class="grid grid-cols-2 gap-4">
                    <x-input label="Regular Price" type="number" name="regular_price" placeholder="0.00" required />
                    <x-input label="Sale Price" type="number" name="sale_price" placeholder="0.00" />
                </div>
                <x-checkbox label="Product is on sale" name="on_sale" />
            </x-card>

            <!-- Inventory -->
            <x-card title="Inventory">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <x-input label="Stock Quantity" type="number" name="stock" placeholder="0" required />
                        <x-input label="Low Stock Threshold" type="number" name="low_stock" placeholder="5" />
                    </div>

                    <x-checkbox label="Track inventory for this product" name="track_inventory" />
                    <x-checkbox label="Allow backorders" name="allow_backorders" />
                </div>
            </x-card>

            <!-- Product Images -->
            <x-card title="Product Images">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Featured Image</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center hover:border-gray-400 transition cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">Click to upload or drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG or WEBP (max. 2MB)</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Gallery Images</label>
                        <div class="grid grid-cols-4 gap-4">
                            @for($i = 1; $i <= 4; $i++)
                            <div class="border-2 border-dashed border-gray-300 rounded-lg aspect-square flex items-center justify-center hover:border-gray-400 transition cursor-pointer">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Shipping -->
            <x-card title="Shipping">
                <div class="grid grid-cols-3 gap-4">
                    <x-input label="Weight (kg)" type="number" step="0.01" name="weight" placeholder="0.00" />
                    <x-input label="Length (cm)" type="number" name="length" placeholder="0" />
                    <x-input label="Width (cm)" type="number" name="width" placeholder="0" />
                </div>
                <x-input label="Height (cm)" type="number" name="height" placeholder="0" />
            </x-card>

            <!-- SEO -->
            <x-card title="SEO">
                <div class="space-y-4">
                    <x-input label="Meta Title" name="meta_title" placeholder="Product name - Brand" />
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Meta Description</label>
                        <textarea rows="3" name="meta_description" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600" placeholder="Enter meta description"></textarea>
                    </div>
                    <x-input label="URL Slug" name="slug" placeholder="product-name" />
                </div>
            </x-card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Publish -->
            <x-card title="Publish">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600">
                            <option>Published</option>
                            <option>Draft</option>
                            <option>Scheduled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Visibility</label>
                        <select name="visibility" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600">
                            <option>Public</option>
                            <option>Private</option>
                            <option>Password Protected</option>
                        </select>
                    </div>

                    <x-checkbox label="Featured Product" name="featured" />

                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <x-button variant="primary" class="w-full">Publish Product</x-button>
                        <x-button variant="outline" class="w-full">Save as Draft</x-button>
                    </div>
                </div>
            </x-card>

            <!-- Category -->
            <x-card title="Category">
                <div class="space-y-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Electronics</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Clothing</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Home & Kitchen</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Sports</span>
                    </label>
                </div>
                <div class="mt-4">
                    <x-link href="#">+ Add New Category</x-link>
                </div>
            </x-card>

            <!-- Tags -->
            <x-card title="Tags">
                <input type="text" name="tags" placeholder="Add tags separated by commas" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600">
                <div class="mt-2 flex flex-wrap gap-2">
                    <x-badge color="blue">New Arrival</x-badge>
                    <x-badge color="green">Best Seller</x-badge>
                    <x-badge color="purple">Limited Edition</x-badge>
                </div>
            </x-card>

            <!-- Brand -->
            <x-card title="Brand">
                <select name="brand" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600">
                    <option>Select Brand</option>
                    <option>Brand A</option>
                    <option>Brand B</option>
                    <option>Brand C</option>
                </select>
                <div class="mt-4">
                    <x-link href="#">+ Add New Brand</x-link>
                </div>
            </x-card>
        </div>
    </form>
</div>
@endsection
