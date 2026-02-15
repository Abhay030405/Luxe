@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8" x-data="{ 
    showRemoveModal: false, 
    removeCartItemId: null, 
    removeProductName: '', 
    addToWishlist: false,
    openRemoveModal(cartItemId, productName) {
        this.removeCartItemId = cartItemId;
        this.removeProductName = productName;
        this.addToWishlist = false;
        this.showRemoveModal = true;
    },
    closeRemoveModal() {
        this.showRemoveModal = false;
        this.removeCartItemId = null;
        this.removeProductName = '';
        this.addToWishlist = false;
    },
    confirmRemove() {
        document.getElementById('remove-form-' + this.removeCartItemId).submit();
    }
}">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @if($cart->totalItems > 0)
    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-8">
            @if(count($validationIssues) > 0)
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-yellow-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Cart Validation Issues</h3>
                        <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside space-y-1">
                            @foreach($validationIssues as $issue)
                            <li>{{ $issue['product_name'] }}: {{ $issue['issue'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <x-card padding="false">
                @foreach($cart->items as $item)
                <div class="flex items-center p-6 border-b border-gray-200 last:border-b-0">
                    <!-- Product Image -->
                    <div class="h-24 w-24 flex-shrink-0 rounded-lg overflow-hidden bg-gray-200">
                        @if($item->primaryImageUrl)
                        <img src="{{ $item->primaryImageUrl }}" alt="{{ $item->productName }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                        </div>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="ml-6 flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">
                                    <a href="{{ route('products.show', $item->productSlug) }}" class="hover:text-blue-600">
                                        {{ $item->productName }}
                                    </a>
                                </h3>
                                @if($item->categoryName)
                                <p class="mt-1 text-sm text-gray-500">Category: {{ $item->categoryName }}</p>
                                @endif
                                @if(!$item->isInStock)
                                <p class="mt-1 text-sm text-red-600 font-medium">Out of Stock</p>
                                @elseif($item->quantity > $item->availableStock)
                                <p class="mt-1 text-sm text-yellow-600 font-medium">Only {{ $item->availableStock }} available</p>
                                @endif
                            </div>
                            <button type="button" @click="openRemoveModal({{ $item->id }}, '{{ addslashes($item->productName) }}')" class="text-gray-400 hover:text-red-600 transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                            <!-- Hidden form for removal -->
                            <form :id="'remove-form-' + {{ $item->id }}" action="{{ route('cart.removeWithWishlist', $item->id) }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="add_to_wishlist" :value="addToWishlist ? '1' : '0'" x-model="addToWishlist">
                            </form>
                        </div>

                        <!-- Quantity & Price -->
                        <div class="mt-4 flex items-center justify-between">
                            <!-- Quantity Selector -->
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button type="button" 
                                            onclick="this.parentNode.querySelector('input[name=quantity]').stepDown(); this.form.submit()"
                                            class="px-3 py-1 text-gray-600 hover:bg-gray-50 transition {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                        </svg>
                                    </button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->availableStock }}" 
                                           class="w-12 text-center border-0 focus:ring-0 text-sm font-medium"
                                           onchange="this.form.submit()">
                                    <button type="button" 
                                            onclick="this.parentNode.querySelector('input[name=quantity]').stepUp(); this.form.submit()"
                                            class="px-3 py-1 text-gray-600 hover:bg-gray-50 transition"
                                            @if($item->quantity >= $item->availableStock) disabled class="opacity-50" @endif>
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </div>
                            </form>

                            <!-- Price -->
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900">{{ currency($item->subtotal) }}</p>
                                <p class="text-sm text-gray-500">Unit: {{ currency($item->priceAtTime) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </x-card>

            <!-- Continue Shopping & Clear Cart -->
            <div class="mt-6 flex items-center justify-between">
                <x-link href="{{ route('products.index') }}">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Continue Shopping
                </x-link>

                <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium" onclick="return confirm('Clear entire cart?')">
                        Clear Cart
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="mt-8 lg:mt-0 lg:col-span-4">
            <x-card title="Order Summary">
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Subtotal ({{ $cart->totalItems }} items)</span>
                        <span class="font-medium text-gray-900">{{ currency($cart->grandTotal) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Total Quantity</span>
                        <span class="font-medium text-gray-900">{{ $cart->totalQuantity }} items</span>
                    </div>

                    <!-- Total -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-gray-900">{{ currency($cart->grandTotal) }}</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    @if(count($validationIssues) > 0)
                    <button disabled class="w-full py-3 px-4 text-base font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed">
                        Fix Issues to Proceed
                    </button>
                    @else
                    <x-button variant="primary" class="w-full" size="lg" href="{{ route('checkout.index') }}">
                        Proceed to Checkout
                    </x-button>
                    @endif

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
    @else
    <!-- Empty Cart State -->
    <div class="text-center py-16">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
        </svg>
        <h2 class="mt-6 text-2xl font-bold text-gray-900">Your cart is empty</h2>
        <p class="mt-2 text-gray-600">Looks like you haven't added anything to your cart yet.</p>
        <div class="mt-8">
            <x-button variant="primary" href="{{ route('products.index') }}">
                Browse Products
            </x-button>
        </div>
    </div>
    @endif

    <!-- Remove Item Modal -->
    <div x-show="showRemoveModal" 
         @keydown.escape.window="closeRemoveModal()"
         class="fixed inset-0 overflow-y-auto" 
         style="z-index: 9999;"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true"
         x-cloak>
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             x-show="showRemoveModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeRemoveModal()"
             aria-hidden="true"></div>

        <!-- Modal Content Container -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Center modal vertically -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showRemoveModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                 @click.stop>
                
                <!-- Icon -->
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Remove from cart?
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to remove <span class="font-semibold" x-text="removeProductName"></span> from your cart?
                            </p>
                        </div>

                        <!-- Wishlist option -->
                        <div class="mt-4">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" x-model="addToWishlist" class="mt-0.5 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <div class="flex-1">
                                    <span class="text-sm font-medium text-gray-700">Add to wishlist</span>
                                    <p class="text-xs text-gray-500">Save this item to your wishlist before removing from cart</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="confirmRemove()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span x-text="addToWishlist ? 'Move to Wishlist' : 'Remove'"></span>
                    </button>
                    <button type="button" 
                            @click="closeRemoveModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
