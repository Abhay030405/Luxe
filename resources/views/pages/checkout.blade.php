@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
            @if(session('checkout_errors'))
                <ul class="mt-2 ml-4 list-disc list-inside">
                    @foreach(session('checkout_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <form action="{{ route('checkout.place') }}" method="POST">
        @csrf

        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-7">
                <!-- Shipping Address Selection -->
                <x-card title="Select Delivery Address" class="mb-6">
                    <div class="space-y-3">
                        @foreach($addresses as $address)
                            <label class="flex items-start p-4 border-2 {{ $address->id === $defaultAddress->id ? 'border-blue-600 bg-blue-50' : 'border-gray-200' }} rounded-lg cursor-pointer hover:border-gray-300 transition">
                                <input 
                                    type="radio" 
                                    name="address_id" 
                                    value="{{ $address->id }}" 
                                    {{ $address->id === $defaultAddress->id ? 'checked' : '' }}
                                    class="mt-1 text-blue-600 focus:ring-blue-600"
                                    required
                                >
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium text-gray-900">{{ $address->full_name }}</p>
                                        @if($address->is_default)
                                            <x-badge color="blue" size="sm">Default</x-badge>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ $address->phone }}</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $address->address_line_1 }}
                                        @if($address->address_line_2), {{ $address->address_line_2 }}@endif
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $address->country }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error('address_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-4">
                        <a href="{{ route('addresses.create') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            + Add New Address
                        </a>
                    </div>
                </x-card>

                <!-- Customer Notes (Optional) -->
                <x-card title="Order Notes (Optional)">
                    <textarea 
                        name="customer_notes" 
                        rows="4" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600" 
                        placeholder="Add any special instructions for your order..."
                    >{{ old('customer_notes') }}</textarea>
                    @error('customer_notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </x-card>
            </div>

            <!-- Order Summary -->
            <div class="mt-8 lg:mt-0 lg:col-span-5">
                <x-card title="Order Summary" class="sticky top-4">
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $cartItem)
                            <div class="flex items-center space-x-4">
                                <div class="relative flex-shrink-0">
                                    <div class="h-16 w-16 rounded-lg bg-gray-200 overflow-hidden">
                                        @if($cartItem->product->images->first())
                                            <img 
                                                src="{{ $cartItem->product->images->first()->image_url }}" 
                                                alt="{{ $cartItem->product->name }}"
                                                class="w-full h-full object-cover"
                                            >
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100"></div>
                                        @endif
                                    </div>
                                    <span class="absolute -top-2 -right-2 h-6 w-6 flex items-center justify-center bg-gray-900 text-white text-xs font-bold rounded-full">
                                        {{ $cartItem->quantity }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $cartItem->product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $cartItem->product->category->name ?? '' }}</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">${{ number_format($cartItem->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pricing Details -->
                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">{{ $orderSummary->getFormattedSubtotal() }}</span>
                        </div>
                        @if($orderSummary->tax > 0)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium text-gray-900">{{ $orderSummary->getFormattedTax() }}</span>
                        </div>
                        @endif
                        @if($orderSummary->shippingFee > 0)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-gray-900">{{ $orderSummary->getFormattedShippingFee() }}</span>
                        </div>
                        @else
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-green-600">FREE</span>
                        </div>
                        @endif
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-gray-900">{{ $orderSummary->getFormattedTotal() }}</span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button 
                        type="submit"
                        class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-2"
                    >
                        <span>Place Order</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        By placing your order, you agree to our Terms & Conditions
                    </p>
                </x-card>
            </div>
        </div>
    </form>
</div>
@endsection
