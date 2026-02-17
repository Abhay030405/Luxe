@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    @if($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    @foreach($errors->all() as $error)
                        <p class="text-sm font-medium text-red-800">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2 text-sm">
            <li><a href="/" class="text-gray-500 hover:text-gray-700">Home</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">Products</a></li>
            @if($product->categoryId)
            <li><span class="text-gray-400">/</span></li>
            <li><a href="{{ route('products.index', ['category_id' => $product->categoryId]) }}" class="text-gray-500 hover:text-gray-700">{{ $product->categoryName }}</a></li>
            @endif
            <li><span class="text-gray-400">/</span></li>
            <li class="text-gray-900 font-medium">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="lg:grid lg:grid-cols-2 lg:gap-x-12">
        <!-- Product Images -->
        <div class="space-y-4">
            <!-- Main Image -->
            <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                @if($product->primaryImageUrl)
                    <img src="{{ $product->primaryImageUrl }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-96 object-cover" id="mainImage">
                @elseif($product->images && $product->images->count() > 0)
                    <img src="{{ $product->images->first()['url'] }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-96 object-cover" id="mainImage">
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                        <svg class="h-48 w-48 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Thumbnail Gallery -->
            @if($product->images && $product->images->count() > 1)
            <div class="grid grid-cols-4 gap-4">
                @foreach($product->images->take(4) as $index => $image)
                <button onclick="changeMainImage('{{ $image['url'] }}')" 
                        class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden border-2 {{ $index == 0 ? 'border-blue-600' : 'border-transparent' }} hover:border-blue-400 transition">
                    <img src="{{ $image['url'] }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-24 object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="mt-8 lg:mt-0">
            <div class="flex items-center justify-between mb-4">
                @if($product->isInStock)
                    <x-badge color="green">In Stock</x-badge>
                @else
                    <x-badge color="red">Out of Stock</x-badge>
                @endif
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                {{ $product->name }}
            </h1>

            @if($product->shortDescription)
            <p class="text-gray-600 mb-6">
                {{ $product->shortDescription }}
            </p>
            @endif

            <!-- Price -->
            <div class="flex items-baseline space-x-4 mb-6">
                @if($product->isOnSale)
                    <span class="text-4xl font-bold text-gray-900">{{ currency($product->salePrice) }}</span>
                    <span class="text-xl text-gray-500 line-through">{{ currency($product->price) }}</span>
                    <x-badge color="red" size="lg">{{ $product->discountPercentage }}% OFF</x-badge>
                @else
                    <span class="text-4xl font-bold text-gray-900">{{ currency($product->price) }}</span>
                @endif
            </div>

            <!-- Features -->
            <div class="border-t border-b border-gray-200 py-6 mb-6 space-y-3">
                <div class="flex items-center text-sm">
                    <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">Free shipping on orders over ₹500</span>
                </div>
                <div class="flex items-center text-sm">
                    <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">7-day return policy</span>
                </div>
                <div class="flex items-center text-sm">
                    <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">Secure payment with COD available</span>
                </div>
            </div>

            <!-- Quantity Selector -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">Quantity</label>
                <form action="{{ route('cart.store') }}" method="POST" id="addToCartForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button type="button" onclick="decreaseQuantity()" class="px-4 py-2 text-gray-600 hover:bg-gray-50 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                </svg>
                            </button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stockQuantity }}" class="w-16 text-center border-0 focus:ring-0 text-gray-900 font-medium">
                            <button type="button" onclick="increaseQuantity()" class="px-4 py-2 text-gray-600 hover:bg-gray-50 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                        @if($product->stockQuantity > 0)
                        <span class="text-sm text-gray-600">Only {{ $product->stockQuantity }} items left in stock</span>
                        @else
                        <span class="text-sm text-red-600 font-medium">Out of Stock</span>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4 mb-6" 
                 x-data="wishlistComponent({{ auth()->check() ? ($isInWishlist ? 'true' : 'false') : 'false' }})">
                @auth
                    @if($product->isInStock)
                    <button form="addToCartForm" type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition cursor-pointer">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        Add to Cart
                    </button>
                    @else
                    <button disabled class="flex-1 inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed">
                        Out of Stock
                    </button>
                    @endif
                    
                    <!-- Wishlist Button -->
                    <button 
                        @click="toggleWishlist()"
                        :disabled="loading"
                        :class="inWishlist ? 'bg-red-50 border-red-500 text-red-600 hover:bg-red-100' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50'"
                        class="inline-flex items-center justify-center px-3 py-3 text-base font-medium border-2 rounded-lg transition disabled:opacity-50 cursor-pointer"
                        :title="inWishlist ? 'Remove from wishlist' : 'Add to wishlist'"
                    >
                        <svg class="h-6 w-6" viewBox="0 0 24 24" stroke-width="1.5" :stroke="inWishlist ? 'currentColor' : 'currentColor'" :fill="inWishlist ? 'currentColor' : 'none'">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="flex-1 inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                        Login to Add to Cart
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-3 py-3 text-base font-medium text-gray-600 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition" title="Login to add to wishlist">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </a>
                @endauth
            </div>

            @if($product->isInStock)
            <form action="{{ route('cart.store') }}" method="POST" id="buyNowForm" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" :value="document.getElementById('quantity').value">
                <input type="hidden" name="buy_now" value="1">
                <button type="submit" :disabled="submitting" class="w-full inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 disabled:opacity-50 cursor-pointer">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                    <span x-text="submitting ? 'Processing...' : 'Buy Now'">Buy Now</span>
                </button>
            </form>
            @endif

            <script>
                function increaseQuantity() {
                    const input = document.getElementById('quantity');
                    const max = parseInt(input.max);
                    const current = parseInt(input.value);
                    if (current < max) {
                        input.value = current + 1;
                    }
                }
                
                function decreaseQuantity() {
                    const input = document.getElementById('quantity');
                    const current = parseInt(input.value);
                    if (current > 1) {
                        input.value = current - 1;
                    }
                }
            </script>

            <!-- Additional Info -->
            <div class="mt-8 space-y-4">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Fast Delivery</p>
                        <p class="text-sm text-gray-600">Delivery within 2-3 business days</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Secure Payment</p>
                        <p class="text-sm text-gray-600">100% secure payment processing</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="mt-16" x-data="{ tab: 'description' }">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8">
                <button @click="tab = 'description'" :class="tab === 'description' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm transition">
                    Description
                </button>
                @if($product->metaData && isset($product->metaData['specifications']))
                <button @click="tab = 'specifications'" :class="tab === 'specifications' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm transition">
                    Specifications
                </button>
                @endif
            </nav>
        </div>

        <!-- Description Tab -->
        <div x-show="tab === 'description'" class="py-8">
            <div class="prose max-w-none">
                @if($product->description)
                    <p class="text-gray-600 whitespace-pre-line">{{ $product->description }}</p>
                @else
                    <p class="text-gray-500 italic">No detailed description available for this product.</p>
                @endif
            </div>
        </div>

        <!-- Specifications Tab -->
        @if($product->metaData && isset($product->metaData['specifications']))
        <div x-show="tab === 'specifications'" class="py-8">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="divide-y divide-gray-200">
                    @foreach($product->metaData['specifications'] as $key => $value)
                    <tr>
                        <td class="py-4 text-sm font-medium text-gray-900 w-1/3">{{ ucfirst($key) }}</td>
                        <td class="py-4 text-sm text-gray-600">{{ $value }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">You May Also Like</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="group">
                <x-card padding="false">
                    <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-t-lg overflow-hidden">
                        @if($relatedProduct->primaryImageUrl)
                            <img src="{{ $relatedProduct->primaryImageUrl }}" 
                                 alt="{{ $relatedProduct->name }}" 
                                 class="w-full h-48 object-cover group-hover:scale-105 transition">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center">
                                <svg class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">{{ $relatedProduct->name }}</h3>
                        <div class="flex items-center justify-between">
                            @if($relatedProduct->isOnSale)
                                <div>
                                    <span class="text-lg font-bold text-gray-900">{{ currency($relatedProduct->salePrice) }}</span>
                                    <span class="text-sm text-gray-500 line-through ml-2">{{ currency($relatedProduct->price) }}</span>
                                </div>
                            @else
                                <span class="text-lg font-bold text-gray-900">{{ currency($relatedProduct->price) }}</span>
                            @endif
                        </div>
                    </div>
                </x-card>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Alpine.js Wishlist Component
    function wishlistComponent(initialState) {
        return {
            inWishlist: initialState,
            loading: false,
            
            toggleWishlist() {
                this.loading = true;

                fetch('{{ route('wishlist.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: {{ $product->id }}
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.inWishlist = !this.inWishlist;
                        
                        // Update wishlist counter in navigation
                        updateWishlistCounter(data.count || 0);
                        
                        // Show notification with the actual message from the API
                        showNotification(data.message || 'Wishlist updated successfully!', 'success');
                    } else {
                        showNotification(data.message || 'Something went wrong', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to update wishlist', 'error');
                })
                .finally(() => {
                    this.loading = false;
                });
            }
        }
    }

    function changeMainImage(imageSrc) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.src = imageSrc;
        }
    }

    function updateWishlistCounter(count) {
        const counterElement = document.getElementById('wishlist-count');
        if (counterElement) {
            counterElement.textContent = count;
            if (count > 0) {
                counterElement.classList.remove('hidden');
            } else {
                counterElement.classList.add('hidden');
            }
        }
    }

    function showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        
        const colors = {
            success: 'bg-green-50 border-green-200 text-green-800',
            error: 'bg-red-50 border-red-200 text-red-800'
        };
        
        const icons = {
            success: '<svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            error: '<svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        };
        
        notification.className = `fixed top-20 right-4 z-50 rounded-lg border p-4 flex items-start space-x-3 shadow-lg ${colors[type]} transform transition-all duration-300 translate-x-0 max-w-md`;
        notification.innerHTML = `
            ${icons[type]}
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="flex-shrink-0 hover:opacity-75 transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Remove after 4 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(150%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.parentElement.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }
</script>
@endpush
@endsection
