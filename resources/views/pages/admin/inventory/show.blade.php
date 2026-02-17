@extends('layouts.admin')

@section('title', 'Inventory Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Inventory Details</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $inventory->product->name }}</p>
        </div>
        <div class="flex gap-3">
            <x-button variant="secondary" href="/admin/inventory">
                Back to Inventory
            </x-button>
            <x-button variant="primary" href="/admin/inventory/{{ $inventory->id }}/edit">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Edit Inventory
            </x-button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <x-card>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500">Available Stock</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $inventory->quantity_available }}</p>
                <p class="mt-1 text-xs text-gray-500">Ready to sell</p>
            </div>
        </x-card>

        <x-card>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500">Reserved Stock</p>
                <p class="mt-2 text-3xl font-bold text-yellow-600">{{ $inventory->quantity_reserved }}</p>
                <p class="mt-1 text-xs text-gray-500">In pending orders</p>
            </div>
        </x-card>

        <x-card>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500">Total Stock</p>
                <p class="mt-2 text-3xl font-bold text-blue-600">{{ $inventory->totalStock }}</p>
                <p class="mt-1 text-xs text-gray-500">Combined inventory</p>
            </div>
        </x-card>

        <x-card>
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500">Low Stock Alert</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $inventory->low_stock_threshold }}</p>
                <p class="mt-1 text-xs text-gray-500">Alert threshold</p>
            </div>
        </x-card>
    </div>

    <!-- Status Card -->
    <x-card>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Current Status</h3>
                <p class="text-sm text-gray-600 mt-1">Inventory health check</p>
            </div>
            <div>
                @if($inventory->isOutOfStock())
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800">
                        <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Out of Stock - Immediate Action Required
                    </span>
                @elseif($inventory->isLowStock())
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Low Stock - Consider Restocking
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-800">
                        <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        Healthy Stock Level
                    </span>
                @endif
            </div>
        </div>
    </x-card>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Product Information -->
        <x-card>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>
            
            @if($inventory->product->primaryImageUrl)
                <img src="{{ $inventory->product->primaryImageUrl }}" alt="{{ $inventory->product->name }}" class="w-full h-64 object-cover rounded-lg mb-4">
            @else
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-20 w-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            @endif

            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Product Name</p>
                        <p class="text-lg font-medium text-gray-900">{{ $inventory->product->name }}</p>
                    </div>
                    <a href="/admin/products/{{ $inventory->product->id }}/edit" class="text-blue-600 hover:text-blue-800 text-sm">
                        View Product
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">SKU</p>
                        <p class="text-base text-gray-900">{{ $inventory->product->sku }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Category</p>
                        <p class="text-base text-gray-900">{{ $inventory->product->category?->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Price</p>
                        <p class="text-base text-gray-900">${{ number_format($inventory->product->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="text-base text-gray-900">{{ ucfirst($inventory->product->status) }}</p>
                    </div>
                </div>

                @if($inventory->product->description)
                <div>
                    <p class="text-sm font-medium text-gray-500">Description</p>
                    <p class="text-sm text-gray-900 mt-1">{{ $inventory->product->description }}</p>
                </div>
                @endif
            </div>
        </x-card>

        <!-- Inventory Timeline & Actions -->
        <div class="space-y-6">
            <!-- Inventory Metadata -->
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Inventory Details</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-500">Created At</span>
                        <span class="text-sm text-gray-900">{{ $inventory->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-500">Last Updated</span>
                        <span class="text-sm text-gray-900">{{ $inventory->updated_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-500">Product ID</span>
                        <span class="text-sm text-gray-900">#{{ $inventory->product_id }}</span>
                    </div>
                </div>
            </x-card>

            <!-- Quick Actions -->
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    <a href="/admin/inventory/{{ $inventory->id }}/edit" class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            <span class="text-sm font-medium text-blue-900">Edit Inventory</span>
                        </div>
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>

                    <a href="/admin/products/{{ $inventory->product->id }}/edit" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-900">View Product Details</span>
                        </div>
                        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>

                    <a href="/admin/inventory/dashboard" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Inventory Dashboard</span>
                        </div>
                        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
