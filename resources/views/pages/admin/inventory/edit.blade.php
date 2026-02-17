@extends('layouts.admin')

@section('title', 'Edit Inventory')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Inventory</h1>
            <p class="mt-1 text-sm text-gray-600">Update stock levels for {{ $inventory->product->name }}</p>
        </div>
        <x-button variant="secondary" href="/admin/inventory">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Inventory
        </x-button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Info Card -->
        <x-card>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>
            
            @if($inventory->product->primaryImageUrl)
                <img src="{{ $inventory->product->primaryImageUrl }}" alt="{{ $inventory->product->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
            @else
                <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            @endif

            <div class="space-y-3">
                <div>
                    <p class="text-sm font-medium text-gray-500">Product Name</p>
                    <p class="text-base text-gray-900">{{ $inventory->product->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">SKU</p>
                    <p class="text-base text-gray-900">{{ $inventory->product->sku }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Price</p>
                    <p class="text-base text-gray-900">${{ number_format($inventory->product->price, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Category</p>
                    <p class="text-base text-gray-900">{{ $inventory->product->category?->name ?? 'N/A' }}</p>
                </div>
            </div>
        </x-card>

        <!-- Main Edit Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Update Stock Form -->
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Stock Levels</h3>
                
                <form method="POST" action="{{ route('admin.inventory.update', $inventory->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="quantity_available" class="block text-sm font-medium text-gray-700 mb-2">
                            Available Quantity
                        </label>
                        <input 
                            type="number" 
                            name="quantity_available" 
                            id="quantity_available" 
                            value="{{ old('quantity_available', $inventory->quantity_available) }}"
                            min="0"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('quantity_available') border-red-500 @enderror"
                        >
                        @error('quantity_available')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">The number of items available for purchase</p>
                    </div>

                    <div>
                        <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-2">
                            Low Stock Threshold
                        </label>
                        <input 
                            type="number" 
                            name="low_stock_threshold" 
                            id="low_stock_threshold" 
                            value="{{ old('low_stock_threshold', $inventory->low_stock_threshold) }}"
                            min="0"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('low_stock_threshold') border-red-500 @enderror"
                        >
                        @error('low_stock_threshold')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Alert when stock falls below this number</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Reserved Quantity</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $inventory->quantity_reserved }}</p>
                                <p class="text-xs text-gray-500 mt-1">Items in pending orders</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Stock</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $inventory->totalStock }}</p>
                                <p class="text-xs text-gray-500 mt-1">Available + Reserved</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="/admin/inventory" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Update Inventory
                        </button>
                    </div>
                </form>
            </x-card>

            <!-- Quick Adjustment Form -->
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Adjustment</h3>
                <p class="text-sm text-gray-600 mb-4">Use this form to quickly add or remove stock with a reason.</p>
                
                <form method="POST" action="{{ route('admin.inventory.adjust', $inventory->id) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="quantity_change" class="block text-sm font-medium text-gray-700 mb-2">
                            Quantity Change
                        </label>
                        <input 
                            type="number" 
                            name="quantity_change" 
                            id="quantity_change" 
                            placeholder="Enter positive to add, negative to remove"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600"
                        >
                        <p class="mt-1 text-sm text-gray-500">Use positive numbers to add stock, negative to remove</p>
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason
                        </label>
                        <select name="reason" id="reason" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600">
                            <option value="manual_adjustment">Manual Adjustment</option>
                            <option value="damaged">Damaged Items</option>
                            <option value="lost">Lost Items</option>
                            <option value="returned">Customer Return</option>
                            <option value="restock">Restock</option>
                            <option value="correction">Inventory Correction</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Apply Adjustment
                    </button>
                </form>
            </x-card>
        </div>
    </div>
</div>
@endsection
