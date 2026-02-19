@extends('layouts.app')

@section('title', 'Edit Product - Vendor Dashboard')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('vendor.products.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Products
            </a>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
            <p class="mt-1 text-sm text-gray-600">Update product information</p>
        </div>

        <!-- Form -->
        <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Product Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                                    Product Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $product->name) }}"
                                       required
                                       class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900 @error('name') border-red-500 @enderror">
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-900 mb-2">
                                    Slug <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       value="{{ old('slug', $product->slug) }}"
                                       required
                                       class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900 @error('slug') border-red-500 @enderror">
                                @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Short Description -->
                            <div>
                                <label for="short_description" class="block text-sm font-medium text-gray-900 mb-2">
                                    Short Description
                                </label>
                                <textarea name="short_description" 
                                          id="short_description" 
                                          rows="2"
                                          class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900">{{ old('short_description', $product->shortDescription) }}</textarea>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-900 mb-2">
                                    Full Description
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="5"
                                          class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Inventory -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Pricing & Inventory</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-900 mb-2">
                                        Price <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-2.5 text-gray-500">₹</span>
                                        <input type="number" 
                                               name="price" 
                                               id="price" 
                                               step="0.01"
                                               value="{{ old('price', $product->price) }}"
                                               required
                                               class="w-full pl-8 pr-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900">
                                    </div>
                                </div>

                                <!-- Sale Price -->
                                <div>
                                    <label for="sale_price" class="block text-sm font-medium text-gray-900 mb-2">
                                        Sale Price
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-2.5 text-gray-500">₹</span>
                                        <input type="number" 
                                               name="sale_price" 
                                               id="sale_price" 
                                               step="0.01"
                                               value="{{ old('sale_price', $product->salePrice) }}"
                                               class="w-full pl-8 pr-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900">
                                    </div>
                                </div>

                                <!-- SKU -->
                                <div>
                                    <label for="sku" class="block text-sm font-medium text-gray-900 mb-2">
                                        SKU
                                    </label>
                                    <input type="text" 
                                           name="sku" 
                                           id="sku" 
                                           value="{{ old('sku', $product->sku) }}"
                                           class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900">
                                </div>

                                <!-- Stock Quantity -->
                                <div>
                                    <label for="stock_quantity" class="block text-sm font-medium text-gray-900 mb-2">
                                        Stock Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="stock_quantity" 
                                           id="stock_quantity" 
                                           value="{{ old('stock_quantity', $product->stockQuantity) }}"
                                           required
                                           class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900">
                                </div>

                                <!-- Weight -->
                                <div>
                                    <label for="weight" class="block text-sm font-medium text-gray-900 mb-2">
                                        Weight (kg)
                                    </label>
                                    <input type="number" 
                                           name="weight" 
                                           id="weight" 
                                           step="0.01"
                                           value="{{ old('weight', $product->weight) }}"
                                           class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Status</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-900 mb-2">
                                    Product Status
                                </label>
                                <select name="status" 
                                        id="status"
                                        class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900">
                                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_featured" 
                                       id="is_featured" 
                                       value="1"
                                       {{ old('is_featured', $product->isFeatured) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-slate-900 focus:ring-slate-900">
                                <label for="is_featured" class="ml-2 text-sm text-gray-700">
                                    Featured Product
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Category</h3>
                        </div>
                        <div class="p-6">
                            <select name="category_id" 
                                    id="category_id"
                                    required
                                    class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->categoryId) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-3">
                        <button type="submit" 
                                class="w-full px-4 py-2.5 bg-slate-900 text-white rounded-lg hover:bg-slate-800 font-medium transition">
                            Update Product
                        </button>
                        <a href="{{ route('vendor.products.index') }}" 
                           class="block w-full px-4 py-2.5 text-center border-2 border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Image Upload Section (Separate Form) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Product Images</h3>
            </div>
            <div class="p-6">
                @if($product->images && $product->images->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    @foreach($product->images as $image)
                    <div class="relative group">
                        <img src="{{ $image['url'] }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                        <form action="{{ route('vendor.products.images.delete', [$product->id, $image['id']]) }}" 
                              method="POST" 
                              class="absolute top-2 right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 text-white p-1.5 rounded opacity-0 group-hover:opacity-100 transition"
                                    onclick="return confirm('Delete this image?')">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                        @if($image['is_primary'])
                        <span class="absolute bottom-2 left-2 bg-slate-900 text-white text-xs px-2 py-1 rounded">
                            Primary
                        </span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 bg-gray-50 rounded-lg mb-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No images uploaded yet</p>
                </div>
                @endif

                <form action="{{ route('vendor.products.images.upload', $product->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      id="image-upload-form">
                    @csrf
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-slate-400 transition">
                        <div class="text-center">
                            <input type="file" name="image" accept="image/*" required class="hidden" id="image-upload" onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'No file chosen'">
                            <label for="image-upload" class="cursor-pointer">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600 font-medium">Click to select an image</p>
                                <p class="mt-1 text-xs text-gray-500" id="file-name">No file chosen</p>
                            </label>
                        </div>
                        <div class="mt-4 text-center">
                            <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white rounded-lg hover:bg-slate-800 font-medium transition">
                                Upload Image
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
