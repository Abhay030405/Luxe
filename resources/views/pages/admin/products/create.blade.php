@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-start">
            <svg class="h-5 w-5 text-green-600 mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-start">
            <svg class="h-5 w-5 text-red-600 mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <p class="text-sm font-medium text-red-900">{{ session('error') }}</p>
        </div>
    </div>
    @endif

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

    <form action="{{ route('admin.products.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <x-card title="Basic Information">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Enter product name" required class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                        <textarea rows="6" name="description" class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('description') border-red-500 @enderror" placeholder="Enter detailed product description...">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">SKU <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" placeholder="PRD-001" required class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('sku') border-red-500 @enderror" value="{{ old('sku') }}">
                            @error('sku')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Barcode</label>
                            <input type="text" name="barcode" placeholder="123456789012" class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600" value="{{ old('barcode') }}">
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Pricing -->
            <x-card title="Pricing">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Regular Price ($) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" placeholder="0.00" required class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('price') border-red-500 @enderror" value="{{ old('price') }}">
                        @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Sale Price ($)</label>
                        <input type="number" step="0.01" name="sale_price" placeholder="0.00" class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('sale_price') border-red-500 @enderror" value="{{ old('sale_price') }}">
                        <p class="mt-1 text-xs text-gray-500">Leave empty if not on sale</p>
                        @error('sale_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-card>

            <!-- Inventory -->
            <x-card title="Inventory">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Stock Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="stock_quantity" placeholder="0" required class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('stock_quantity') border-red-500 @enderror" value="{{ old('stock_quantity', 0) }}">
                        @error('stock_quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Low Stock Alert</label>
                        <input type="number" name="low_stock_threshold" placeholder="5" class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600" value="{{ old('low_stock_threshold', 10) }}">
                        <p class="mt-1 text-xs text-gray-500">Alert when stock is below this</p>
                    </div>
                </div>
            </x-card>

            <!-- Product Images -->
            <x-card title="Product Images">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900">Images can be added after creating the product</p>
                            <p class="text-xs text-blue-700 mt-1">After saving, you'll be redirected to add product images</p>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Shipping -->
            <x-card title="Shipping Info (Optional)">
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" placeholder="0.00" class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600" value="{{ old('weight') }}">
                    </div>
                </div>
            </x-card>

            <!-- Additional Details -->
            <x-card title="Additional Details (Optional)">
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Short Description</label>
                        <textarea rows="2" name="short_description" placeholder="Brief product summary..." class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600">{{ old('short_description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Max 500 characters</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">URL Slug</label>
                        <input type="text" name="slug" placeholder="product-name" class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600" value="{{ old('slug') }}">
                        <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from product name</p>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Publish -->
            <x-card title="Publish">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                            <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Display on homepage</p>
                    </div>

                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            Create Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="block w-full px-4 py-2 text-center border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                            Cancel
                        </a>
                    </div>
                </div>
            </x-card>

            <!-- Category -->
            <x-card title="Category">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Select Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full px-4 py-3 text-base rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('category_id') border-red-500 @enderror">
                        <option value="">Choose a category</option>
                        @foreach($categories as $category)
                            @if(!$category->parentId)
                                <!-- Parent Category -->
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="font-semibold">{{ $category->name }}</option>
                                
                                <!-- Subcategories -->
                                @foreach($categories->where('parentId', $category->id) as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('category_id') == $subcategory->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;{{ $subcategory->name }}
                                    </option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Select the most specific category for your product</p>
                    @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.categories.create') }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add New Category
                    </a>
                </div>
            </x-card>
        </div>
    </form>
</div>
@endsection
