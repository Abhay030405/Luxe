@extends('layouts.admin')

@section('title', 'Create Inventory Record')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Inventory Record</h1>
            <p class="mt-1 text-sm text-gray-600">Add inventory tracking for a product</p>
        </div>
        <x-button variant="secondary" href="/admin/inventory">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Inventory
        </x-button>
    </div>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('admin.inventory.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Product <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="product_id" 
                        id="product_id" 
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('product_id') border-red-500 @enderror"
                    >
                        <option value="">-- Select a Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($products->isEmpty())
                        <p class="mt-2 text-sm text-yellow-600">
                            All products already have inventory records. 
                            <a href="/admin/inventory" class="font-medium underline">Manage existing inventory</a>
                        </p>
                    @else
                        <p class="mt-1 text-sm text-gray-500">Choose a product that doesn't have an inventory record yet</p>
                    @endif
                </div>

                <div>
                    <label for="quantity_available" class="block text-sm font-medium text-gray-700 mb-2">
                        Initial Available Quantity <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="quantity_available" 
                        id="quantity_available" 
                        value="{{ old('quantity_available', 0) }}"
                        min="0"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('quantity_available') border-red-500 @enderror"
                    >
                    @error('quantity_available')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">The initial number of items available for purchase</p>
                </div>

                <div>
                    <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-2">
                        Low Stock Threshold <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="low_stock_threshold" 
                        id="low_stock_threshold" 
                        value="{{ old('low_stock_threshold', 10) }}"
                        min="0"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 @error('low_stock_threshold') border-red-500 @enderror"
                    >
                    @error('low_stock_threshold')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">You'll be alerted when stock falls below this number</p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-900">About Inventory Tracking</h3>
                            <div class="mt-2 text-sm text-blue-800">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Available quantity is the stock ready for immediate sale</li>
                                    <li>Reserved quantity will be managed automatically when orders are placed</li>
                                    <li>Low stock alerts help you restock before running out</li>
                                    <li>You can adjust inventory levels anytime after creation</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="/admin/inventory" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                        {{ $products->isEmpty() ? 'disabled' : '' }}
                    >
                        Create Inventory Record
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection
