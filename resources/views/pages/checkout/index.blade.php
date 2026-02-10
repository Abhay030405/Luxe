@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <!-- Progress Steps -->
    <div class="mb-12">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-semibold">1</div>
                <span class="ml-3 font-medium text-blue-600">Shipping</span>
            </div>
            <div class="flex-1 h-0.5 mx-4 bg-gray-300"></div>
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-semibold">2</div>
                <span class="ml-3 font-medium text-gray-500">Payment</span>
            </div>
            <div class="flex-1 h-0.5 mx-4 bg-gray-300"></div>
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-semibold">3</div>
                <span class="ml-3 font-medium text-gray-500">Review</span>
            </div>
        </div>
    </div>

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-7">
            <!-- Shipping Address -->
            <x-card title="Shipping Address" class="mb-6">
                <form class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <x-input label="First Name" name="first_name" value="John" required />
                        <x-input label="Last Name" name="last_name" value="Doe" required />
                    </div>
                    
                    <x-input label="Email Address" type="email" name="email" value="john@example.com" required />
                    <x-input label="Phone Number" type="tel" name="phone" value="+1 234 567 8900" required />
                    <x-input label="Street Address" name="address" value="123 Main Street" required />
                    
                    <div class="grid grid-cols-2 gap-4">
                        <x-input label="City" name="city" value="New York" required />
                        <x-input label="State / Province" name="state" value="NY" required />
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <x-input label="ZIP / Postal Code" name="zip" value="10001" required />
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Country</label>
                            <select name="country" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600">
                                <option>United States</option>
                                <option>Canada</option>
                                <option>United Kingdom</option>
                                <option>Australia</option>
                            </select>
                        </div>
                    </div>

                    <x-checkbox label="Save this address for future orders" name="save_address" />
                </form>
            </x-card>

            <!-- Shipping Method -->
            <x-card title="Shipping Method" class="mb-6">
                <div class="space-y-3">
                    <label class="flex items-center justify-between p-4 border-2 border-blue-600 rounded-lg cursor-pointer bg-blue-50 transition">
                        <div class="flex items-center">
                            <input type="radio" name="shipping" value="standard" checked class="text-blue-600 focus:ring-blue-600">
                            <div class="ml-3">
                                <p class="font-medium text-gray-900">Standard Shipping</p>
                                <p class="text-sm text-gray-600">Delivery in 5-7 business days</p>
                            </div>
                        </div>
                        <span class="font-semibold text-gray-900">$15.00</span>
                    </label>

                    <label class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-gray-300 transition">
                        <div class="flex items-center">
                            <input type="radio" name="shipping" value="express" class="text-blue-600 focus:ring-blue-600">
                            <div class="ml-3">
                                <p class="font-medium text-gray-900">Express Shipping</p>
                                <p class="text-sm text-gray-600">Delivery in 2-3 business days</p>
                            </div>
                        </div>
                        <span class="font-semibold text-gray-900">$29.99</span>
                    </label>

                    <label class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-gray-300 transition">
                        <div class="flex items-center">
                            <input type="radio" name="shipping" value="overnight" class="text-blue-600 focus:ring-blue-600">
                            <div class="ml-3">
                                <p class="font-medium text-gray-900">Overnight Shipping</p>
                                <p class="text-sm text-gray-600">Delivery next business day</p>
                            </div>
                        </div>
                        <span class="font-semibold text-gray-900">$49.99</span>
                    </label>
                </div>
            </x-card>

            <!-- Additional Notes -->
            <x-card title="Order Notes (Optional)">
                <textarea rows="4" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600" placeholder="Add any special instructions for your order..."></textarea>
            </x-card>
        </div>

        <!-- Order Summary -->
        <div class="mt-8 lg:mt-0 lg:col-span-5">
            <x-card title="Order Summary" class="sticky top-4">
                <!-- Cart Items -->
                <div class="space-y-4 mb-6">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="flex items-center space-x-4">
                        <div class="relative flex-shrink-0">
                            <div class="h-16 w-16 rounded-lg bg-gray-200 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100"></div>
                            </div>
                            <span class="absolute -top-2 -right-2 h-6 w-6 flex items-center justify-center bg-gray-900 text-white text-xs font-bold rounded-full">{{ rand(1, 3) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">Product Name {{ $i }}</p>
                            <p class="text-sm text-gray-500">Black, M</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">${{ rand(50, 200) }}.99</span>
                    </div>
                    @endfor
                </div>

                <!-- Pricing Details -->
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">$359.97</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Shipping (Standard)</span>
                        <span class="font-medium text-gray-900">$15.00</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium text-gray-900">$37.50</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-green-600">
                        <span class="font-medium">Discount (SAVE20)</span>
                        <span class="font-medium">-$71.99</span>
                    </div>

                    <!-- Total -->
                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-gray-900">$340.48</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 space-y-3">
                    <x-button variant="primary" class="w-full" size="lg">
                        Continue to Payment
                    </x-button>
                    <x-button variant="outline" class="w-full">
                        Back to Cart
                    </x-button>
                </div>

                <!-- Security Notice -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Secure Checkout</p>
                            <p class="text-xs text-gray-600 mt-1">Your payment information is encrypted and secure</p>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
