@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Products</h1>
            <p class="mt-2 text-sm text-gray-600">Browse our collection of amazing products</p>
        </div>
        <div class="flex items-center space-x-3">
            <select class="rounded-lg border-gray-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                <option>Sort by: Popular</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Newest</option>
                <option>Best Rating</option>
            </select>
        </div>
    </div>

    <div class="lg:grid lg:grid-cols-4 lg:gap-8">
        <!-- Filters Sidebar -->
        <aside class="hidden lg:block">
            <div class="sticky top-4 space-y-6">
                <!-- Categories Filter -->
                <x-card title="Categories" padding="false">
                    <div class="px-6 py-4 space-y-3">
                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                            <span class="ml-3 text-sm text-gray-700">Electronics</span>
                            <span class="ml-auto text-xs text-gray-500">(45)</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                            <span class="ml-3 text-sm text-gray-700">Clothing</span>
                            <span class="ml-auto text-xs text-gray-500">(123)</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                            <span class="ml-3 text-sm text-gray-700">Home & Kitchen</span>
                            <span class="ml-auto text-xs text-gray-500">(67)</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                            <span class="ml-3 text-sm text-gray-700">Sports</span>
                            <span class="ml-auto text-xs text-gray-500">(89)</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                            <span class="ml-3 text-sm text-gray-700">Books</span>
                            <span class="ml-auto text-xs text-gray-500">(234)</span>
                        </label>
                    </div>
                </x-card>

                <!-- Price Range Filter -->
                <x-card title="Price Range" padding="false">
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                            <input type="number" placeholder="$0" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                            <input type="number" placeholder="$1000" class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                        </div>
                        <x-button variant="primary" class="w-full" size="sm">Apply</x-button>
                    </div>
                </x-card>

                <!-- Rating Filter -->
                <x-card title="Rating" padding="false">
                    <div class="px-6 py-4 space-y-2">
                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                            <span class="ml-3 flex items-center text-sm text-gray-700">
                                <span class="text-yellow-400">★★★★★</span>
                                <span class="ml-2">(5 stars)</span>
                            </span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                            <span class="ml-3 flex items-center text-sm text-gray-700">
                                <span class="text-yellow-400">★★★★☆</span>
                                <span class="ml-2">(4 & up)</span>
                            </span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                            <span class="ml-3 flex items-center text-sm text-gray-700">
                                <span class="text-yellow-400">★★★☆☆</span>
                                <span class="ml-2">(3 & up)</span>
                            </span>
                        </label>
                    </div>
                </x-card>
            </div>
        </aside>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Product Card 1 -->
                @for($i = 1; $i <= 9; $i++)
                <div class="group relative bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">
                    <!-- Product Image -->
                    <div class="aspect-w-1 aspect-h-1 bg-gray-200 overflow-hidden relative">
                        <div class="w-full h-64 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                            <svg class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                        </div>
                        <!-- Wishlist Button -->
                        <button class="absolute top-3 right-3 p-2 rounded-full bg-white shadow-md hover:bg-red-50 transition">
                            <svg class="h-5 w-5 text-gray-600 hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </button>
                        <!-- Sale Badge -->
                        @if($i % 3 == 0)
                        <x-badge color="red" class="absolute top-3 left-3">SALE</x-badge>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1 group-hover:text-blue-600 transition">
                            Product Name {{ $i }}
                        </h3>
                        <p class="text-xs text-gray-500 mb-2">Category Name</p>
                        
                        <!-- Rating -->
                        <div class="flex items-center mb-2">
                            <span class="text-yellow-400 text-sm">★★★★☆</span>
                            <span class="ml-2 text-xs text-gray-600">({{ rand(10, 500) }} reviews)</span>
                        </div>

                        <!-- Price -->
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <span class="text-lg font-bold text-gray-900">${{ rand(20, 500) }}.99</span>
                                @if($i % 3 == 0)
                                <span class="ml-2 text-sm text-gray-500 line-through">${{ rand(600, 800) }}.99</span>
                                @endif
                            </div>
                        </div>

                        <!-- Add to Cart Button -->
                        <x-button variant="primary" class="w-full" size="sm">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            Add to Cart
                        </x-button>
                    </div>

                    <!-- Quick View (appears on hover) -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 pointer-events-none"></div>
                </div>
                @endfor
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex items-center justify-center space-x-2">
                <button class="px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Previous
                </button>
                <button class="px-4 py-2 rounded-lg bg-blue-600 text-sm font-medium text-white">1</button>
                <button class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">2</button>
                <button class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">3</button>
                <span class="px-3 py-2 text-sm text-gray-500">...</span>
                <button class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">10</button>
                <button class="px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
