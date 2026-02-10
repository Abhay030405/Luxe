@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
        
        <!-- Filter Dropdown -->
        <select class="rounded-lg border-gray-300 text-sm focus:border-blue-600 focus:ring-blue-600">
            <option>All Orders</option>
            <option>Processing</option>
            <option>Shipped</option>
            <option>Delivered</option>
            <option>Cancelled</option>
        </select>
    </div>

    <!-- Orders List -->
    <div class="space-y-6">
        @for($i = 1; $i <= 4; $i++)
        <x-card padding="false">
            <!-- Order Header -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between flex-wrap gap-4">
                <div class="space-y-1">
                    <div class="flex items-center space-x-4">
                        <p class="text-sm">
                            <span class="font-medium text-gray-900">Order #</span>
                            <span class="font-mono">ORD-2026-{{ rand(1000, 9999) }}</span>
                        </p>
                        <span class="text-gray-300">|</span>
                        <p class="text-sm text-gray-600">{{ date('M d, Y', strtotime('-'.($i*5).' days')) }}</p>
                    </div>
                    <p class="text-xs text-gray-500">Total: ${{ rand(100, 500) }}.99</p>
                </div>
                
                @php
                    $statuses = ['Processing', 'Shipped', 'Delivered', 'Cancelled'];
                    $colors = ['blue', 'purple', 'green', 'red'];
                    $statusIndex = $i % 4;
                @endphp
                <x-badge :color="$colors[$statusIndex]" size="lg">{{ $statuses[$statusIndex] }}</x-badge>
            </div>

            <!-- Order Items -->
            <div class="px-6 py-4">
                <div class="space-y-4 mb-4">
                    @for($j = 1; $j <= 2; $j++)
                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-20 flex-shrink-0 rounded-lg bg-gray-200 overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100"></div>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Product Name {{ $j }}</p>
                            <p class="text-sm text-gray-500">Quantity: {{ rand(1, 3) }}</p>
                            <p class="text-sm font-semibold text-gray-700 mt-1">${{ rand(50, 200) }}.99</p>
                        </div>
                    </div>
                    @endfor
                </div>

                <!-- Order Actions -->
                <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                    <x-button variant="primary" size="sm">View Details</x-button>
                    @if($statusIndex != 3)
                    <x-button variant="outline" size="sm">Track Order</x-button>
                    @endif
                    @if($statusIndex == 2)
                    <x-button variant="success" size="sm">Write Review</x-button>
                    @endif
                    @if($statusIndex == 0)
                    <x-button variant="danger" size="sm">Cancel Order</x-button>
                    @endif
                </div>
            </div>
        </x-card>
        @endfor
    </div>

    <!-- Empty State (When no orders) -->
    {{-- <x-empty-state 
        title="No orders yet"
        description="You haven't placed any orders yet. Start shopping to see your orders here!"
        icon="bag">
        <x-slot:action>
            <x-button variant="primary" href="/products">
                Start Shopping
            </x-button>
        </x-slot:action>
    </x-empty-state> --}}

    <!-- Pagination -->
    <div class="mt-8 flex items-center justify-center space-x-2">
        <button class="px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            Previous
        </button>
        <button class="px-4 py-2 rounded-lg bg-blue-600 text-sm font-medium text-white">1</button>
        <button class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">2</button>
        <button class="px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            Next
        </button>
    </div>
</div>
@endsection
