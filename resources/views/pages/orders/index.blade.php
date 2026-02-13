@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            @if(isset($stats))
                <p class="mt-2 text-sm text-gray-600">
                    You've placed {{ $stats['total_orders'] }} {{ Str::plural('order', $stats['total_orders']) }} totaling {{ '$' . number_format($stats['total_spent'], 2) }}
                </p>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <!-- Empty State -->
        <x-card>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No orders yet</h3>
                <p class="mt-1 text-sm text-gray-500">You haven't placed any orders yet. Start shopping to see your orders here!</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Start Shopping
                    </a>
                </div>
            </div>
        </x-card>
    @else
        <!-- Orders List -->
        <div class="space-y-6">
            @foreach($orders as $order)
                <x-card padding="false">
                    <!-- Order Header -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between flex-wrap gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-4">
                                <p class="text-sm">
                                    <span class="font-medium text-gray-900">Order #</span>
                                    <span class="font-mono">{{ $order->order_number }}</span>
                                </p>
                                <span class="text-gray-300">|</span>
                                <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }} • Total: {{ $order->formatted_total }}
                            </p>
                        </div>
                        
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $order->status->badgeClass() }}">
                            {{ $order->status->label() }}
                        </span>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="px-6 py-4">
                        <div class="space-y-4 mb-4">
                            @foreach($order->items->take(2) as $item)
                                <div class="flex items-center space-x-4">
                                    <div class="h-20 w-20 flex-shrink-0 rounded-lg bg-gray-200 overflow-hidden">
                                        @if($item->product && $item->product->images->first())
                                            <img 
                                                src="{{ $item->product->images->first()->image_url }}" 
                                                alt="{{ $item->product_name }}"
                                                class="w-full h-full object-cover"
                                            >
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                                <span class="text-gray-400 text-xs font-medium">No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                        <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                        <p class="text-sm font-semibold text-gray-700 mt-1">{{ $item->formatted_price }}</p>
                                    </div>
                                </div>
                            @endforeach

                            @if($order->items->count() > 2)
                                <p class="text-sm text-gray-500 pl-24">
                                    + {{ $order->items->count() - 2 }} more {{ Str::plural('item', $order->items->count() - 2) }}
                                </p>
                            @endif
                        </div>

                        <!-- Order Actions -->
                        <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                View Details
                            </a>

                            @if($order->canBeCancelled())
                                <button 
                                    onclick="event.preventDefault(); if(confirm('Are you sure you want to cancel this order?')) document.getElementById('cancel-order-{{ $order->id }}').submit();"
                                    class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50"
                                >
                                    Cancel Order
                                </button>
                                <form id="cancel-order-{{ $order->id }}" action="{{ route('orders.cancel', $order->id) }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="reason" value="Cancelled by customer">
                                </form>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
