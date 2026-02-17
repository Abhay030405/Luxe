@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Products</h1>
            <p class="mt-2 text-sm text-gray-600">Browse our collection of premium items</p>
        </div>
        
        <!-- Mobile Filter Dialog Toggle (Hidden on desktop) -->
        <button type="button" class="lg:hidden p-2 text-gray-400 hover:text-gray-500">
            <span class="sr-only">Filters</span>
            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div class="lg:grid lg:grid-cols-4 lg:gap-8">
        <!-- Filters Sidebar -->
        <aside class="hidden lg:block space-y-6">
            <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                <!-- Hidden input to preserve sort_by when filters change -->
                <input type="hidden" name="sort_by" id="filter-sort-by" value="{{ $filters['sort_by'] ?? 'created_at' }}">
                
                <!-- Categories Filter -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-900">Categories</h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <label class="flex items-center cursor-pointer group">
                            <input 
                                type="radio" 
                                name="category_id" 
                                value="" 
                                {{ empty($filters['category_id']) ? 'checked' : '' }}
                                class="h-4 w-4 rounded-full border-gray-300 text-slate-900 focus:ring-slate-900 transition duration-150 ease-in-out"
                                onchange="document.getElementById('filter-form').submit()"
                            >
                            <span class="ml-3 text-sm text-gray-600 group-hover:text-slate-900 transition">All Categories</span>
                        </label>
                        @foreach($categories as $category)
                        <label class="flex items-center cursor-pointer group">
                            <input 
                                type="radio" 
                                name="category_id" 
                                value="{{ $category->id }}" 
                                {{ ($filters['category_id'] ?? '') == $category->id ? 'checked' : '' }}
                                class="h-4 w-4 rounded-full border-gray-300 text-slate-900 focus:ring-slate-900 transition duration-150 ease-in-out"
                                onchange="document.getElementById('filter-form').submit()"
                            >
                            <span class="ml-3 text-sm text-gray-600 group-hover:text-slate-900 transition">{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-900">Price Range</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Min</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">{{ currency_symbol() }}</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        name="min_price" 
                                        value="{{ $filters['min_price'] ?? '' }}" 
                                        class="block w-full rounded-md border-gray-300 pl-7 py-2 focus:border-slate-900 focus:ring-slate-900 sm:text-sm"
                                        placeholder="0"
                                    >
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Max</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">{{ currency_symbol() }}</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        name="max_price" 
                                        value="{{ $filters['max_price'] ?? '' }}" 
                                        class="block w-full rounded-md border-gray-300 pl-7 py-2 focus:border-slate-900 focus:ring-slate-900 sm:text-sm"
                                        placeholder="1000"
                                    >
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-colors">
                            Apply Filter
                        </button>
                    </div>
                </div>

                <!-- Featured Toggle -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                     <div class="p-5">
                        <label class="flex items-center cursor-pointer group">
                             <input 
                                 type="checkbox" 
                                 name="is_featured" 
                                 value="1" 
                                 {{ !empty($filters['is_featured']) ? 'checked' : '' }}
                                 class="h-4 w-4 rounded border-gray-300 text-slate-900 focus:ring-slate-900"
                                 onchange="document.getElementById('filter-form').submit()"
                             >
                             <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-slate-900">Featured Products Only</span>
                         </label>
                     </div>
                </div>

                <!-- Reset Filters -->
                <div class="text-center">
                    <a href="{{ route('products.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 hover:underline">
                        Clear all filters
                    </a>
                </div>
            </form>
            </aside>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                <!-- Sorting Bar -->
                <div class="flex justify-end mb-6">
                    <select 
                        name="sort_by"
                        onchange="document.getElementById('filter-sort-by').value = this.value; document.getElementById('filter-form').submit();"
                        class="rounded-lg border-gray-300 text-sm focus:border-slate-900 focus:ring-slate-900 py-2 pl-3 pr-10"
                    >
                        <option value="created_at" {{ ($filters['sort_by'] ?? 'created_at') == 'created_at' ? 'selected' : '' }}>Newest Arrivals</option>
                        <option value="price_asc" {{ ($filters['sort_by'] ?? '') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ ($filters['sort_by'] ?? '') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name" {{ ($filters['sort_by'] ?? '') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                    </select>
                </div>

                @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <div class="group relative bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <!-- Product Image -->
                        <a href="{{ route('products.show', $product->slug) }}" class="block aspect-w-1 aspect-h-1 bg-gray-100 overflow-hidden relative">
                            @if(!empty($product->primaryImageUrl))
                                <img src="{{ $product->primaryImageUrl }}" alt="{{ $product->name }}" class="w-full h-64 object-cover object-center group-hover:scale-105 transition-transform duration-500">
                            @else
                                <img src="/images/default.png" alt="{{ $product->name ?? 'Product Image' }}" class="w-full h-64 object-cover object-center group-hover:scale-105 transition-transform duration-500 opacity-90">
                            @endif
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-2">
                                @if($product->isOnSale)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        SALE {{ $product->discountPercentage }}%
                                    </span>
                                @endif
                                @if($product->isFeatured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-900 text-white">
                                        FEATURED
                                    </span>
                                @endif
                            </div>

                            @if(!$product->isInStock)
                                <div class="absolute inset-0 bg-white/60 flex items-center justify-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gray-900 text-white shadow-lg">
                                        OUT OF STOCK
                                    </span>
                                </div>
                            @endif
                        </a>

                        <!-- Product Info -->
                        <div class="p-4">
                            <p class="text-xs font-medium text-slate-500 mb-1">{{ $product->categoryName ?? 'General' }}</p>
                            <a href="{{ route('products.show', $product->slug) }}">
                                <h3 class="text-sm font-bold text-gray-900 mb-2 group-hover:text-slate-700 transition line-clamp-1">
                                    {{ $product->name ?? 'Sample Product' }}
                                </h3>
                            </a>

                            <!-- Price -->
                            <div class="mb-3">
                                @if($product->isOnSale)
                                    <span class="text-lg font-bold text-slate-900">{{ currency($product->salePrice) }}</span>
                                    <span class="ml-2 text-xs font-medium text-gray-400 line-through">{{ currency($product->price) }}</span>
                                @else
                                    <span class="text-lg font-bold text-slate-900">{{ currency($product->price) }}</span>
                                @endif
                            </div>
                            
                            <!-- Add to Cart Button -->
                            @auth
                                <form action="{{ route('cart.store') }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    @if($product->isInStock)
                                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-slate-900 hover:bg-slate-800 rounded-lg transition-colors duration-200 cursor-pointer">
                                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            Add to Cart
                                        </button>
                                    @else
                                        <button type="button" disabled class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                            Out of Stock
                                        </button>
                                    @endif
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 text-sm font-medium text-slate-900 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                    Login to Add to Cart
                                </a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $products->withQueryString()->links('vendor.pagination.tailwind') }}
                </div>
                @else
                <!-- No Products Found -->
                <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <div class="mx-auto h-24 w-24 text-gray-200 mb-6">
                         <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">No products found</h2>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">We couldn't find any products matching your current filters. Try adjusting your search criteria.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-800">
                        Clear all filters
                    </a>
                </div>
                @endif
            </div>
        </div>
</div>
@endsection
