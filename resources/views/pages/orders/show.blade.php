@extends('layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Orders
        </a>
        
        <div class="mt-4 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
                <p class="mt-2 text-sm text-gray-600">Order # <span class="font-mono font-medium">{{ $order->order_number }}</span></p>
            </div>
            <span class="px-4 py-2 text-sm font-medium rounded-full {{ $order->status->badgeClass() }}">
                {{ $order->status->label() }}
            </span>
        </div>
    </div>

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-8">
            <!-- Order Items -->
            <x-card title="Order Items" class="mb-6">
                <div class="space-y-6">
                    @foreach($order->items as $item)
                        <div class="flex items-center space-x-4 pb-6 border-b border-gray-200 last:border-0 last:pb-0">
                            <div class="h-24 w-24 shrink-0 rounded-lg bg-gray-200 overflow-hidden">
                                @if($item->product && $item->product->images && $item->product->images->count() > 0)
                                    <img 
                                        src="{{ $item->product->images->first()->image_url }}" 
                                        alt="{{ $item->product_name }}"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <div class="w-full h-full bg-linear-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item->product_name }}</h3>
                                        @if($item->product_sku)
                                            <p class="text-sm text-gray-500 mt-1">SKU: {{ $item->product_sku }}</p>
                                        @endif
                                        <p class="text-sm text-gray-600 mt-1">Quantity: {{ $item->quantity }}</p>
                                        <p class="text-sm text-gray-600">Price: {{ $item->formatted_price }} each</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">{{ $item->formatted_subtotal }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>

            <!-- Shipping Address -->
            <x-card title="Shipping Address" class="mb-6">
                <div class="text-sm">
                    <p class="font-medium text-gray-900">{{ $order->shipping_address['full_name'] }}</p>
                    <p class="text-gray-600 mt-2">{{ $order->shipping_address['phone'] }}</p>
                    <p class="text-gray-600 mt-2">
                        {{ $order->shipping_address['address_line_1'] }}
                        @if(!empty($order->shipping_address['address_line_2']))
                            <br>{{ $order->shipping_address['address_line_2'] }}
                        @endif
                    </p>
                    <p class="text-gray-600">
                        {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['postal_code'] }}
                    </p>
                    <p class="text-gray-600">{{ $order->shipping_address['country'] }}</p>
                </div>
            </x-card>

            @if($order->customer_notes)
                <!-- Customer Notes -->
                <x-card title="Customer Notes">
                    <p class="text-sm text-gray-700">{{ $order->customer_notes }}</p>
                </x-card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="mt-8 lg:mt-0 lg:col-span-4">
            <!-- Order Summary -->
            <x-card title="Order Summary" class="mb-6 sticky top-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Order Date</span>
                        <span class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Order Time</span>
                        <span class="font-medium text-gray-900">{{ $order->created_at->format('h:i A') }}</span>
                    </div>

                    <div class="border-t border-gray-200 pt-3 mt-3"></div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">{{ currency($order->subtotal) }}</span>
                    </div>

                    @if($order->tax > 0)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium text-gray-900">{{ currency($order->tax) }}</span>
                    </div>
                    @endif

                    @if($order->shipping_fee > 0)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium text-gray-900">{{ currency($order->shipping_fee) }}</span>
                    </div>
                    @else
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium text-green-600">FREE</span>
                    </div>
                    @endif

                    <div class="border-t border-gray-200 pt-3 mt-3"></div>

                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-gray-900">{{ currency($order->total_amount) }}</span>
                    </div>
                </div>

                @if($order->canBeCancelled())
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            @csrf
                            <input type="hidden" name="reason" value="Cancelled by customer">
                            <button 
                                type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                            >
                                Cancel Order
                            </button>
                        </form>
                    </div>
                @endif
            </x-card>

            <!-- Order Timeline / Status (For future enhancement) -->
            <x-card title="Order Status">
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="shrink-0 w-2 h-2 mt-2 rounded-full {{ $order->status->isFinal() ? 'bg-green-500' : 'bg-blue-500' }}"></div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $order->status->label() }}</p>
                            <p class="text-sm text-gray-500">{{ $order->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
