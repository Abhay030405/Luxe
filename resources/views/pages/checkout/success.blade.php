@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
    <!-- Success Message -->
    <div class="text-center py-12">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-6">
            <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
        <p class="text-lg text-gray-600 mb-2">Thank you for your purchase</p>
        <p class="text-sm text-gray-500">
            Order #<span class="font-mono font-medium">ORD-2026-{{ rand(1000, 9999) }}</span>
        </p>
    </div>

    <!-- Order Details -->
    <x-card class="mb-6">
        <div class="space-y-6">
            <!-- Estimated Delivery -->
            <div class="text-center p-6 bg-blue-50 rounded-lg">
                <p class="text-sm font-medium text-blue-900 mb-1">Estimated Delivery</p>
                <p class="text-2xl font-bold text-blue-600">{{ date('M d, Y', strtotime('+5 days')) }}</p>
                <p class="text-sm text-blue-700 mt-2">We'll send you tracking information as soon as your order ships</p>
            </div>

            <!-- Order Items -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                <div class="space-y-4">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-20 flex-shrink-0 rounded-lg bg-gray-200 overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100"></div>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Product Name {{ $i }}</p>
                            <p class="text-sm text-gray-500">Quantity: {{ rand(1, 3) }}</p>
                        </div>
                        <span class="font-semibold text-gray-900">${{ rand(50, 200) }}.99</span>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Shipping Address</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p>John Doe</p>
                        <p>123 Main Street</p>
                        <p>New York, NY 10001</p>
                        <p>United States</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Payment Method</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p>Visa ending in 3456</p>
                        <p class="text-green-600 font-medium">Payment successful</p>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Order Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-gray-900">$359.97</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="text-gray-900">$15.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax</span>
                        <span class="text-gray-900">$37.50</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span class="font-medium">Discount</span>
                        <span class="font-medium">-$71.99</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-200">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="font-bold text-gray-900">$340.48</span>
                    </div>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 mb-12">
        <x-button variant="primary" class="flex-1">View Order Details</x-button>
        <x-button variant="outline" class="flex-1" href="/">Continue Shopping</x-button>
    </div>

    <!-- What's Next -->
    <x-card title="What happens next?" class="mb-12">
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">1</div>
                </div>
                <div class="ml-4">
                    <p class="font-medium text-gray-900">Order Confirmation</p>
                    <p class="text-sm text-gray-600">You'll receive an email confirmation shortly with your order details</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">2</div>
                </div>
                <div class="ml-4">
                    <p class="font-medium text-gray-900">Order Processing</p>
                    <p class="text-sm text-gray-600">We'll start preparing your order for shipment right away</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">3</div>
                </div>
                <div class="ml-4">
                    <p class="font-medium text-gray-900">Shipping Notification</p>
                    <p class="text-sm text-gray-600">You'll get tracking information once your order ships</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">4</div>
                </div>
                <div class="ml-4">
                    <p class="font-medium text-gray-900">Delivery</p>
                    <p class="text-sm text-gray-600">Your order will arrive by {{ date('M d, Y', strtotime('+5 days')) }}</p>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Help Section -->
    <div class="text-center pb-12">
        <p class="text-gray-600 mb-4">Need help with your order?</p>
        <x-link href="#">Contact Customer Support</x-link>
    </div>
</div>
@endsection
