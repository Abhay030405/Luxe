@extends('layouts.app')

@section('title', 'My Wishlist - ' . config('app.name'))

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Wishlist</h1>
                <p class="mt-2 text-gray-600">{{ $wishlistItems->count() }} {{ Str::plural('item', $wishlistItems->count()) }} saved</p>
            </div>
            @if($wishlistItems->isNotEmpty())
            <form action="{{ route('wishlist.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your entire wishlist?')">
                @csrf
                <x-button variant="outline" type="submit">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Clear All
                </x-button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-6" />
    @endif

    @if(session('error'))
        <x-alert type="error" :message="session('error')" class="mb-6" />
    @endif

    <!-- Wishlist Items -->
    @if($wishlistItems->isEmpty())
        <x-empty-state 
            title="Your wishlist is empty" 
            description="Start adding products you love to your wishlist"
        >
            <a href="{{ route('products.index') }}">
                <x-button variant="primary" size="lg">
                    Browse Products
                </x-button>
            </a>
        </x-empty-state>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($wishlistItems as $item)
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden group relative">
                <!-- Remove Button -->
                <form action="{{ route('wishlist.destroy', $item->productId) }}" method="POST" class="absolute top-2 right-2 z-10">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 bg-white rounded-full shadow-lg hover:bg-red-50 transition opacity-0 group-hover:opacity-100" title="Remove from wishlist">
                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </form>

                <!-- Product Image -->
                <a href="{{ route('products.show', $item->product->slug) }}" class="block aspect-square bg-gray-100 overflow-hidden">
                    @if($item->product->primaryImageUrl)
                        <img src="{{ $item->product->primaryImageUrl }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center">
                            <svg class="h-24 w-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                        </div>
                    @endif
                </a>

                <!-- Product Details -->
                <div class="p-4">
                    <!-- Stock Badge -->
                    <div class="mb-2">
                        @if($item->product->isInStock)
                            <x-badge color="green" size="sm">In Stock</x-badge>
                        @else
                            <x-badge color="red" size="sm">Out of Stock</x-badge>
                        @endif
                    </div>

                    <!-- Product Name -->
                    <a href="{{ route('products.show', $item->product->slug) }}" class="block">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-blue-600 transition">
                            {{ $item->product->name }}
                        </h3>
                    </a>

                    <!-- Price -->
                    <div class="mb-3">
                        @if($item->product->isOnSale)
                            <div class="flex items-center space-x-2">
                                <span class="text-lg font-bold text-gray-900">{{ currency($item->product->salePrice) }}</span>
                                <span class="text-sm text-gray-500 line-through">{{ currency($item->product->price) }}</span>
                            </div>
                        @else
                            <span class="text-lg font-bold text-gray-900">{{ currency($item->product->price) }}</span>
                        @endif
                    </div>

                    <!-- Add to Cart Button -->
                    @if($item->product->isInStock)
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->productId }}">
                            <input type="hidden" name="quantity" value="1">
                            <x-button variant="primary" type="submit" class="w-full" size="sm">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                </svg>
                                Add to Cart
                            </x-button>
                        </form>
                    @else
                        <x-button variant="outline" disabled class="w-full" size="sm">
                            Out of Stock
                        </x-button>
                    @endif

                    <!-- Added Date -->
                    <p class="text-xs text-gray-500 mt-3">Added {{ $item->addedAt }}</p>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
