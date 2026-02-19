@extends('layouts.app')

@section('title', 'Add New Product - Vendor Dashboard')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
                <p class="mt-1 text-sm text-gray-600">Create a new product for your store</p>
            </div>
            <a href="{{ route('vendor.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Products
            </a>
        </div>

        <form action="{{ route('vendor.products.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="Enter product name" required class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900 @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Description</label>
                            <textarea rows="6" name="description" class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900 @error('description') border-red-500 @enderror" placeholder="Enter detailed product description...">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">SKU <span class="text-red-500">*</span></label>
                                <input type="text" name="sku" placeholder="PRD-001" required class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900 @error('sku') border-red-500 @enderror" value="{{ old('sku') }}">
                                @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Barcode</label>
                                <input type="text" name="barcode" placeholder="123456789012" class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900" value="{{ old('barcode') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pricing</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Regular Price (₹) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="price" placeholder="0.00" required class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900 @error('price') border-red-500 @enderror" value="{{ old('price') }}">
                                @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Sale Price (₹)</label>
                                <input type="number" step="0.01" name="sale_price" placeholder="0.00" class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900 @error('sale_price') border-red-500 @enderror" value="{{ old('sale_price') }}">
                                <p class="mt-1 text-xs text-gray-500">Leave empty if not on sale</p>
                                @error('sale_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Inventory</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Stock Quantity <span class="text-red-500">*</span></label>
                                <input type="number" name="stock_quantity" placeholder="0" required class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900 @error('stock_quantity') border-red-500 @enderror" value="{{ old('stock_quantity', 0) }}">
                                @error('stock_quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">Low Stock Alert</label>
                                <input type="number" name="low_stock_threshold" placeholder="5" class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900" value="{{ old('low_stock_threshold', 10) }}">
                                <p class="mt-1 text-xs text-gray-500">Alert when stock is below this</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Product Images</h3>
                    </div>
                    <div class="p-6">
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
                    </div>
                </div>

                <!-- Shipping -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Shipping Info (Optional)</h3>
                    </div>
                    <div class="p-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" placeholder="0.00" class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900" value="{{ old('weight') }}">
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Additional Details (Optional)</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Short Description</label>
                            <textarea rows="2" name="short_description" placeholder="Brief product summary..." class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900">{{ old('short_description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Max 500 characters</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">URL Slug</label>
                            <input type="text" name="slug" placeholder="product-name" class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900" value="{{ old('slug') }}">
                            <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from product name</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publish -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Publish</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900 @error('status') border-red-500 @enderror">
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
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-slate-900 focus:ring-slate-900">
                                <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Display prominently in store</p>
                        </div>

                        <div class="pt-4 border-t border-gray-200 space-y-3">
                            <button type="submit" class="w-full px-4 py-2.5 bg-slate-900 text-white rounded-lg hover:bg-slate-800 font-medium transition">
                                Create Product
                            </button>
                            <a href="{{ route('vendor.products.index') }}" class="block w-full px-4 py-2.5 text-center border-2 border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Category</h3>
                    </div>
                    <div class="p-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Select Category <span class="text-red-500">*</span></label>
                            <select name="category_id" required class="w-full px-4 py-2.5 text-base rounded-lg border-2 border-gray-300 focus:border-slate-900 focus:ring-slate-900 @error('category_id') border-red-500 @enderror">
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
                    </div>
                </div>

                <!-- Vendor Info Notice -->
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-slate-600 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-slate-900">Product Ownership</p>
                            <p class="text-xs text-slate-700 mt-1">This product will be automatically linked to your vendor account.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
