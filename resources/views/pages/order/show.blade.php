@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <x-link href="/orders">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Orders
        </x-link>
    </div>

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-8">
            <!-- Order Header -->
            <x-card class="mb-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            Order #ORD-2026-{{ rand(1000, 9999) }}
                        </h1>
                        <p class="text-sm text-gray-600">Placed on {{ date('M d, Y') }}</p>
                    </div>
                    <x-badge color="blue" size="lg">Processing</x-badge>
                </div>

                <!-- Order Timeline -->
                <div class="relative">
                    <div class="absolute left-4 top-8 bottom-8 w-0.5 bg-gray-200"></div>
                    
                    <div class="space-y-6">
                        <!-- Order Placed -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-600 text-white z-10">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Order Placed</p>
                                <p class="text-sm text-gray-600">{{ date('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Order Confirmed -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-600 text-white z-10">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Order Confirmed</p>
                                <p class="text-sm text-gray-600">{{ date('M d, Y \a\t h:i A', strtotime('+1 hour')) }}</p>
                            </div>
                        </div>

                        <!-- Processing -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white z-10">
                                <x-spinner size="4" />
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">Processing</p>
                                <p class="text-sm text-gray-600">Your order is being prepared</p>
                            </div>
                        </div>

                        <!-- Shipped (Upcoming) -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-600 z-10">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.229-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-500">Shipped</p>
                                <p class="text-sm text-gray-500">Estimated {{ date('M d, Y', strtotime('+2 days')) }}</p>
                            </div>
                        </div>

                        <!-- Delivered (Upcoming) -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-600 z-10">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-500">Delivered</p>
                                <p class="text-sm text-gray-500">Estimated {{ date('M d, Y', strtotime('+5 days')) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Order Items -->
            <x-card title="Order Items">
                <div class="space-y-4">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 last:border-b-0 last:pb-0">
                        <div class="h-24 w-24 flex-shrink-0 rounded-lg bg-gray-200 overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100"></div>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Product Name {{ $i }}</p>
                            <p class="text-sm text-gray-500 mt-1">Color: Black, Size: M</p>
                            <p class="text-sm text-gray-500">Quantity: {{ rand(1, 3) }}</p>
                            <p class="text-sm font-semibold text-gray-700 mt-2">${{ rand(50, 200) }}.99</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </x-card>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="mt-8 lg:mt-0 lg:col-span-4">
            <x-card title="Order Summary" class="mb-6 sticky top-4">
                <div class="space-y-3 mb-6">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
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
                    <div class="flex items-center justify-between text-sm text-green-600">
                        <span class="font-medium">Discount</span>
                        <span class="font-medium">-$71.99</span>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-xl font-bold text-gray-900">$340.48</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <x-button variant="primary" class="w-full">Track Package</x-button>
                    <x-button variant="outline" class="w-full">Download Invoice</x-button>
                    <x-button variant="danger" class="w-full">Cancel Order</x-button>
                </div>
            </x-card>

            <!-- Shipping Address -->
            <x-card title="Shipping Address" class="mb-6">
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-medium text-gray-900">John Doe</p>
                    <p>123 Main Street</p>
                    <p>New York, NY 10001</p>
                    <p>United States</p>
                    <p class="pt-2">Phone: +1 234 567 8900</p>
                </div>
            </x-card>

            <!-- Payment Method -->
            <x-card title="Payment Method">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        <p class="font-medium text-gray-900">Visa •••• 3456</p>
                        <p class="mt-1">Paid on {{ date('M d, Y') }}</p>
                    </div>
                    <x-badge color="green">Paid</x-badge>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Need Help -->
    <x-card class="mt-8">
        <div class="text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Need Help?</h3>
            <p class="text-gray-600 mb-4">Have questions about your order? We're here to help!</p>
            <div class="flex justify-center space-x-4">
                <x-button variant="outline">Contact Support</x-button>
                <x-button variant="outline">Start Live Chat</x-button>
            </div>
        </div>
    </x-card>
</div>
@endsection
