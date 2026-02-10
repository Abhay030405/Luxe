@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-8">
            <x-card padding="false">
                <!-- Cart Item 1 -->
                @for($i = 1; $i <= 3; $i++)
                <div class="flex items-center p-6 border-b border-gray-200 last:border-b-0">
                    <!-- Product Image -->
                    <div class="h-24 w-24 flex-shrink-0 rounded-lg overflow-hidden bg-gray-200">
                        <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100"></div>
                    </div>

                    <!-- Product Details -->
                    <div class="ml-6 flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">
                                    Product Name {{ $i }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">Color: Black, Size: M</p>
                                <p class="mt-1 text-sm text-gray-500">SKU: PRD-{{ rand(1000, 9999) }}</p>
                            </div>
                            <button class="text-gray-400 hover:text-red-600 transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>

                        <!-- Quantity & Price -->
                        <div class="mt-4 flex items-center justify-between">
                            <!-- Quantity Selector -->
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button class="px-3 py-1 text-gray-600 hover:bg-gray-50 transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                    </svg>
                                </button>
                                <input type="number" value="{{ rand(1, 3) }}" min="1" class="w-12 text-center border-0 focus:ring-0 text-sm font-medium">
                                <button class="px-3 py-1 text-gray-600 hover:bg-gray-50 transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Price -->
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900">${{ $price = rand(50, 200) }}.99</p>
                                <p class="text-sm text-gray-500">Unit: ${{ $price }}.99</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </x-card>

            <!-- Continue Shopping -->
            <div class="mt-6">
                <x-link href="/products">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Continue Shopping
                </x-link>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="mt-8 lg:mt-0 lg:col-span-4">
            <x-card title="Order Summary">
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Subtotal (3 items)</span>
                        <span class="font-medium text-gray-900">$359.97</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium text-gray-900">$15.00</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium text-gray-900">$37.50</span>
                    </div>

                    <!-- Discount Code -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex space-x-2">
                            <input type="text" placeholder="Discount code" class="flex-1 rounded-lg border-gray-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                            <x-button variant="outline" size="sm">Apply</x-button>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-gray-900">$412.47</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <x-button variant="primary" class="w-full" size="lg">
                        Proceed to Checkout
                    </x-button>

                    <!-- Security Badge -->
                    <div class="flex items-center justify-center text-sm text-gray-500 pt-4">
                        <svg class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        Secure checkout
                    </div>
                </div>
            </x-card>

            <!-- Accepted Payment Methods -->
            <x-card class="mt-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">We Accept</h3>
                <div class="flex items-center space-x-3">
                    <div class="px-3 py-2 bg-white border border-gray-200 rounded text-xs font-medium">VISA</div>
                    <div class="px-3 py-2 bg-white border border-gray-200 rounded text-xs font-medium">MC</div>
                    <div class="px-3 py-2 bg-white border border-gray-200 rounded text-xs font-medium">AMEX</div>
                    <div class="px-3 py-2 bg-white border border-gray-200 rounded text-xs font-medium">PayPal</div>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Empty Cart State (Hidden when cart has items) -->
    {{-- <x-empty-state 
        title="Your cart is empty"
        description="Looks like you haven't added anything to your cart yet. Start shopping to fill it up!"
        icon="cart">
        <x-slot:action>
            <x-button variant="primary" href="/products">
                Browse Products
            </x-button>
        </x-slot:action>
    </x-empty-state> --}}
</div>
@endsection
