@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2 text-sm">
            <li><a href="/" class="text-gray-500 hover:text-gray-700">Home</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li><a href="#" class="text-gray-500 hover:text-gray-700">Electronics</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li class="text-gray-900 font-medium">Product Name</li>
        </ol>
    </nav>

    <div class="lg:grid lg:grid-cols-2 lg:gap-x-12">
        <!-- Product Images -->
        <div class="space-y-4">
            <!-- Main Image -->
            <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                <div class="w-full h-96 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                    <svg class="h-48 w-48 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </div>
            </div>

            <!-- Thumbnail Gallery -->
            <div class="grid grid-cols-4 gap-4">
                @for($i = 1; $i <= 4; $i++)
                <button class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden border-2 {{ $i == 1 ? 'border-blue-600' : 'border-transparent' }} hover:border-blue-400 transition">
                    <div class="w-full h-24 bg-gradient-to-br from-blue-50 to-purple-50"></div>
                </button>
                @endfor
            </div>
        </div>

        <!-- Product Info -->
        <div class="mt-8 lg:mt-0">
            <div class="flex items-center justify-between mb-4">
                <x-badge color="green">In Stock</x-badge>
                <div class="flex items-center space-x-2">
                    <span class="text-yellow-400 text-lg">★★★★☆</span>
                    <span class="text-sm text-gray-600">(4.5/5 from 234 reviews)</span>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Premium Wireless Headphones
            </h1>

            <p class="text-gray-600 mb-6">
                Experience superior sound quality with our premium wireless headphones. Features active noise cancellation, 30-hour battery life, and premium comfort.
            </p>

            <!-- Price -->
            <div class="flex items-baseline space-x-4 mb-6">
                <span class="text-4xl font-bold text-gray-900">$299.99</span>
                <span class="text-xl text-gray-500 line-through">$399.99</span>
                <x-badge color="red" size="lg">25% OFF</x-badge>
            </div>

            <!-- Features -->
            <div class="border-t border-b border-gray-200 py-6 mb-6 space-y-3">
                <div class="flex items-center text-sm">
                    <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">Free shipping on orders over $50</span>
                </div>
                <div class="flex items-center text-sm">
                    <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">30-day money-back guarantee</span>
                </div>
                <div class="flex items-center text-sm">
                    <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-gray-700">1-year warranty included</span>
                </div>
            </div>

            <!-- Quantity Selector -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">Quantity</label>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <button class="px-4 py-2 text-gray-600 hover:bg-gray-50 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                            </svg>
                        </button>
                        <input type="number" value="1" min="1" class="w-16 text-center border-0 focus:ring-0 text-gray-900 font-medium">
                        <button class="px-4 py-2 text-gray-600 hover:bg-gray-50 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>
                    <span class="text-sm text-gray-600">Only 12 items left in stock</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4 mb-6">
                <x-button variant="primary" class="flex-1" size="lg">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                    Add to Cart
                </x-button>
                <x-button variant="outline" size="lg">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </x-button>
            </div>

            <x-button variant="success" class="w-full" size="lg">Buy Now</x-button>

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
                <button @click="tab = 'specifications'" :class="tab === 'specifications' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm transition">
                    Specifications
                </button>
                <button @click="tab = 'reviews'" :class="tab === 'reviews' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm transition">
                    Reviews (234)
                </button>
            </nav>
        </div>

        <!-- Description Tab -->
        <div x-show="tab === 'description'" class="py-8">
            <div class="prose max-w-none">
                <p class="text-gray-600">
                    Experience premium audio quality with our flagship wireless headphones. Designed for audiophiles and casual listeners alike, these headphones deliver exceptional sound clarity, deep bass, and crisp highs.
                </p>
                <h3 class="text-lg font-semibold text-gray-900 mt-6">Key Features:</h3>
                <ul class="space-y-2 text-gray-600">
                    <li>• Active Noise Cancellation (ANC) for immersive listening</li>
                    <li>• 30-hour battery life on a single charge</li>
                    <li>• Premium memory foam ear cushions for all-day comfort</li>
                    <li>• Bluetooth 5.0 for stable, wireless connectivity</li>
                    <li>• Built-in microphone for hands-free calling</li>
                    <li>• Foldable design with carrying case included</li>
                </ul>
            </div>
        </div>

        <!-- Specifications Tab -->
        <div x-show="tab === 'specifications'" class="py-8">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="divide-y divide-gray-200">
                    <tr><td class="py-4 text-sm font-medium text-gray-900 w-1/3">Brand</td><td class="py-4 text-sm text-gray-600">Premium Audio</td></tr>
                    <tr><td class="py-4 text-sm font-medium text-gray-900">Model</td><td class="py-4 text-sm text-gray-600">PA-2024-PRO</td></tr>
                    <tr><td class="py-4 text-sm font-medium text-gray-900">Connectivity</td><td class="py-4 text-sm text-gray-600">Bluetooth 5.0, 3.5mm aux</td></tr>
                    <tr><td class="py-4 text-sm font-medium text-gray-900">Battery Life</td><td class="py-4 text-sm text-gray-600">30 hours</td></tr>
                    <tr><td class="py-4 text-sm font-medium text-gray-900">Weight</td><td class="py-4 text-sm text-gray-600">250g</td></tr>
                    <tr><td class="py-4 text-sm font-medium text-gray-900">Warranty</td><td class="py-4 text-sm text-gray-600">1 Year</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Reviews Tab -->
        <div x-show="tab === 'reviews'" class="py-8 space-y-6">
            @for($i = 1; $i <= 3; $i++)
            <div class="border-b border-gray-200 pb-6">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <p class="font-medium text-gray-900">John Doe</p>
                        <p class="text-sm text-gray-500">Verified Purchase</p>
                    </div>
                    <span class="text-yellow-400">★★★★★</span>
                </div>
                <p class="text-gray-600 mt-2">
                    Amazing sound quality! The noise cancellation works perfectly on my daily commute. Battery life is excellent, and they're very comfortable even after hours of use.
                </p>
                <p class="text-sm text-gray-500 mt-2">Posted on January 15, 2026</p>
            </div>
            @endfor
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">You May Also Like</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @for($i = 1; $i <= 4; $i++)
            <x-card padding="false">
                <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                    <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-purple-50"></div>
                </div>
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Similar Product {{ $i }}</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-900">${{ rand(50, 300) }}.99</span>
                        <span class="text-yellow-400 text-sm">★★★★☆</span>
                    </div>
                </div>
            </x-card>
            @endfor
        </div>
    </div>
</div>
@endsection
