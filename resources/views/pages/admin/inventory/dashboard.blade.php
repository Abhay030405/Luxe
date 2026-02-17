@extends('layouts.admin')

@section('title', 'Inventory Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Inventory Dashboard</h1>
            <p class="mt-1 text-sm text-gray-600">Monitor stock levels and alerts</p>
        </div>
        <div class="flex gap-3">
            <x-button variant="secondary" href="/admin/inventory">
                View All Inventory
            </x-button>
            <x-button variant="primary" href="/admin/inventory/create">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Inventory
            </x-button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Out of Stock -->
        <x-card>
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-red-100 rounded-lg">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $outOfStockCount }}</p>
                </div>
            </div>
        </x-card>

        <!-- Low Stock -->
        <x-card>
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-yellow-100 rounded-lg">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Low Stock</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $lowStockCount }}</p>
                </div>
            </div>
        </x-card>

        <!-- Total Items Tracked -->
        <x-card>
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Items Tracked</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $lowStockProducts->count() + $outOfStockProducts->count() }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Out of Stock Products -->
    @if($outOfStockProducts->count() > 0)
    <x-card>
        <div class="border-b border-gray-200 pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Out of Stock Products</h2>
            <p class="text-sm text-gray-600 mt-1">These products need immediate restocking</p>
        </div>

        <div class="space-y-3">
            @foreach($outOfStockProducts as $inventory)
            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                <div class="flex items-center flex-1">
                    @if($inventory->product->primaryImageUrl)
                        <img src="{{ $inventory->product->primaryImageUrl }}" alt="{{ $inventory->product->name }}" class="h-12 w-12 rounded-lg object-cover">
                    @else
                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    @endif
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">{{ $inventory->product->name }}</h3>
                        <p class="text-xs text-gray-500">SKU: {{ $inventory->product->sku }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-red-900">{{ $inventory->quantity_available }} available</p>
                        <p class="text-xs text-gray-500">{{ $inventory->quantity_reserved }} reserved</p>
                    </div>
                    <a href="/admin/inventory/{{ $inventory->id }}/edit" class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50">
                        Restock
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </x-card>
    @endif

    <!-- Low Stock Products -->
    @if($lowStockProducts->count() > 0)
    <x-card>
        <div class="border-b border-gray-200 pb-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Low Stock Products</h2>
            <p class="text-sm text-gray-600 mt-1">These products are running low and may need restocking soon</p>
        </div>

        <div class="space-y-3">
            @foreach($lowStockProducts as $inventory)
            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <div class="flex items-center flex-1">
                    @if($inventory->product->primaryImageUrl)
                        <img src="{{ $inventory->product->primaryImageUrl }}" alt="{{ $inventory->product->name }}" class="h-12 w-12 rounded-lg object-cover">
                    @else
                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    @endif
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900">{{ $inventory->product->name }}</h3>
                        <p class="text-xs text-gray-500">SKU: {{ $inventory->product->sku }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-yellow-900">{{ $inventory->quantity_available }} available</p>
                        <p class="text-xs text-gray-500">Threshold: {{ $inventory->low_stock_threshold }}</p>
                    </div>
                    <a href="/admin/inventory/{{ $inventory->id }}/edit" class="px-4 py-2 text-sm font-medium text-yellow-700 bg-white border border-yellow-300 rounded-lg hover:bg-yellow-50">
                        Adjust
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </x-card>
    @endif

    <!-- No Issues -->
    @if($outOfStockProducts->count() === 0 && $lowStockProducts->count() === 0)
    <x-card>
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">All Good!</h3>
            <p class="mt-2 text-sm text-gray-600">No stock level alerts at the moment. All inventory levels are healthy.</p>
            <div class="mt-6">
                <x-button variant="primary" href="/admin/inventory">
                    View All Inventory
                </x-button>
            </div>
        </div>
    </x-card>
    @endif
</div>
@endsection
