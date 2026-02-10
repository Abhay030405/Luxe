@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Payment</h1>

    <!-- Progress Steps -->
    <div class="mb-12">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-600 text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <span class="ml-3 font-medium text-green-600">Shipping</span>
            </div>
            <div class="flex-1 h-0.5 mx-4 bg-green-600"></div>
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-semibold">2</div>
                <span class="ml-3 font-medium text-blue-600">Payment</span>
            </div>
            <div class="flex-1 h-0.5 mx-4 bg-gray-300"></div>
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-semibold">3</div>
                <span class="ml-3 font-medium text-gray-500">Review</span>
            </div>
        </div>
    </div>

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Payment Form -->
        <div class="lg:col-span-7">
            <!-- Payment Method Selection -->
            <x-card title="Payment Method" class="mb-6">
                <div class="space-y-3">
                    <label class="flex items-center p-4 border-2 border-blue-600 rounded-lg cursor-pointer bg-blue-50">
                        <input type="radio" name="payment_method" value="card" checked class="text-blue-600 focus:ring-blue-600">
                        <span class="ml-3 font-medium text-gray-900">Credit / Debit Card</span>
                    </label>

                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-gray-300">
                        <input type="radio" name="payment_method" value="paypal" class="text-blue-600 focus:ring-blue-600">
                        <span class="ml-3 font-medium text-gray-900">PayPal</span>
                    </label>

                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-gray-300">
                        <input type="radio" name="payment_method" value="bank" class="text-blue-600 focus:ring-blue-600">
                        <span class="ml-3 font-medium text-gray-900">Bank Transfer</span>
                    </label>
                </div>
            </x-card>

            <!-- Card Details -->
            <x-card title="Card Details">
                <form class="space-y-4">
                    <x-input label="Card Number" name="card_number" placeholder="1234 5678 9012 3456" required />

                    <x-input label="Cardholder Name" name="card_name" placeholder="John Doe" required />

                    <div class="grid grid-cols-2 gap-4">
                        <x-input label="Expiry Date" name="expiry" placeholder="MM / YY" required />
                        <x-input label="CVV" name="cvv" placeholder="123" required />
                    </div>

                    <x-checkbox label="Save this card for future purchases" name="save_card" />

                    <!-- Billing Address Same as Shipping -->
                    <div class="pt-4 border-t border-gray-200">
                        <x-checkbox label="Billing address same as shipping" name="same_address" />
                    </div>
                </form>
            </x-card>
        </div>

        <!-- Order Summary -->
        <div class="mt-8 lg:mt-0 lg:col-span-5">
            <x-card title="Order Summary" class="sticky top-4">
                <!-- Cart Items Count -->
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Items in cart</span>
                    <span class="text-sm font-medium text-gray-900">3 items</span>
                </div>

                <!-- Pricing Details -->
                <div class="space-y-3 mb-6">
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
                        <span class="font-medium">Discount</span>
                        <span class="font-medium">-$71.99</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="pt-4 border-t border-gray-200 mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-base font-semibold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-gray-900">$340.48</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Including $37.50 in taxes</p>
                </div>

                <!-- Shipping Address Summary -->
                <div class="p-4 bg-gray-50 rounded-lg mb-6">
                    <p class="text-sm font-medium text-gray-900 mb-2">Shipping to:</p>
                    <p class="text-sm text-gray-600">John Doe</p>
                    <p class="text-sm text-gray-600">123 Main Street</p>
                    <p class="text-sm text-gray-600">New York, NY 10001</p>
                    <p class="text-sm text-gray-600">United States</p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <x-button variant="primary" class="w-full" size="lg">
                        Place Order
                    </x-button>
                    <x-button variant="outline" class="w-full">
                        Back to Shipping
                    </x-button>
                </div>

                <!-- Terms -->
                <p class="text-xs text-gray-500 text-center mt-4">
                    By placing your order, you agree to our <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                </p>
            </x-card>
        </div>
    </div>
</div>
@endsection
