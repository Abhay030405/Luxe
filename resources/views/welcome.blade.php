<<<<<<< HEAD
@extends('layouts.app')

@section('title', 'Home - Premium Fashion Store')

@section('content')
{{-- Hero Section / Main Banner --}}
<div class="relative overflow-hidden bg-gray-900">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1920&h=1080&fit=crop" 
             alt="Fashion Banner" 
             class="h-full w-full object-cover opacity-60">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>
    
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="min-h-[70vh] flex flex-col justify-center">
            <div class="max-w-2xl">
                <h1 class="text-5xl font-bold tracking-tight text-white sm:text-7xl mb-6">
                    Summer 2026 Collection
                </h1>
                <p class="text-xl leading-8 text-gray-200 mb-8">
                    Premium streetwear crafted for everyday comfort
                </p>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-x-6">
                    <a href="{{ route('products.index') }}" 
                       class="rounded-md bg-white px-8 py-4 text-base font-semibold text-gray-900 shadow-lg hover:bg-gray-100 hover:shadow-xl focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transform hover:-translate-y-0.5 transition">
                        Shop Now
                    </a>
                    <a href="#new-arrivals" class="text-base font-semibold leading-7 text-white hover:text-gray-200 transition">
                        Explore Collection <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Category Highlights --}}
@if($categories->isNotEmpty())
<div class="bg-white py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-16">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Shop by Category</h2>
            <p class="mt-4 text-lg text-gray-600">Find exactly what you're looking for</p>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
               class="group relative overflow-hidden rounded-2xl aspect-square bg-gray-100 hover:shadow-xl transition">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&h=600&fit=crop" 
                     alt="{{ $category->name }}" 
                     class="h-full w-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 flex items-end p-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-white">{{ $category->name }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Brand Statement / Quote Section --}}
<div class="bg-gray-50 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <blockquote class="text-3xl sm:text-4xl font-serif italic text-gray-900 leading-relaxed">
                "Clothing is not just what you wear — it is how you present yourself to the world."
            </blockquote>
            <div class="mt-8 h-1 w-24 mx-auto bg-gray-300"></div>
        </div>
    </div>
</div>

{{-- New Arrivals Section --}}
@if($newArrivals->isNotEmpty())
<div id="new-arrivals" class="bg-white py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">New Arrivals</h2>
                <p class="mt-4 text-lg text-gray-600">Check out our latest collection</p>
            </div>
            <a href="{{ route('products.index') }}" 
               class="hidden sm:flex items-center text-base font-semibold text-blue-600 hover:text-purple-600 transition">
                View All
                <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @foreach($newArrivals as $product)
            <div class="group">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="aspect-square overflow-hidden rounded-lg bg-gray-100 mb-4">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="h-full w-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-gray-200">
                                <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 mb-2 group-hover:text-blue-600 transition">
                        {{ $product->name }}
                    </h3>
                    <div class="flex items-center gap-2">
                        @if($product->sale_price)
                            <p class="text-lg font-bold text-gray-900">${{ number_format($product->sale_price, 2) }}</p>
                            <p class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</p>
                        @else
                            <p class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-10 text-center sm:hidden">
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center text-base font-semibold text-blue-600 hover:text-purple-600 transition">
                View All New Arrivals
                <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
</div>
@endif

{{-- Featured Collection --}}
@if($featuredProducts->isNotEmpty())
<div class="bg-gray-50 py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-12">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Editor's Picks</h2>
            <p class="mt-4 text-lg text-gray-600">Handpicked favorites just for you</p>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @foreach($featuredProducts as $product)
            <div class="group">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="relative aspect-square overflow-hidden rounded-lg bg-gray-100 mb-4">
                        <span class="absolute top-2 right-2 z-10 rounded-full bg-black/80 px-3 py-1 text-xs font-semibold text-white">
                            Featured
                        </span>
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="h-full w-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-gray-200">
                                <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 mb-2 group-hover:text-blue-600 transition">
                        {{ $product->name }}
                    </h3>
                    <div class="flex items-center gap-2">
                        @if($product->sale_price)
                            <p class="text-lg font-bold text-gray-900">${{ number_format($product->sale_price, 2) }}</p>
                            <p class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</p>
                        @else
                            <p class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Why Choose Us / Trust Section --}}
<div class="bg-white py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-16">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Why Choose Us</h2>
            <p class="mt-4 text-lg text-gray-600">We're committed to providing the best shopping experience</p>
        </div>

        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Free Shipping --}}
            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 mb-6">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Free Shipping</h3>
                <p class="text-sm text-gray-600">On all orders over $50</p>
            </div>

            {{-- Easy Returns --}}
            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 mb-6">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Easy Returns</h3>
                <p class="text-sm text-gray-600">30-day return policy</p>
            </div>

            {{-- Secure Payment --}}
            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-purple-100 mb-6">
                    <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Secure Payment</h3>
                <p class="text-sm text-gray-600">100% secure transactions</p>
            </div>

            {{-- Premium Quality --}}
            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100 mb-6">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Premium Quality</h3>
                <p class="text-sm text-gray-600">Only the finest materials</p>
            </div>
        </div>
    </div>
</div>

{{-- Testimonials Section --}}
<div class="bg-gray-900 py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-16">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">What Our Customers Say</h2>
            <p class="mt-4 text-lg text-gray-400">Real reviews from real people</p>
        </div>

        <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
            {{-- Testimonial 1 --}}
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 transition">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    @endfor
                </div>
                <p class="text-gray-300 mb-6 italic">"Best quality clothing I've purchased online. The fabric is amazing and the fit is perfect!"</p>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gray-700"></div>
                    <div>
                        <p class="font-semibold text-white">Sarah Johnson</p>
                        <p class="text-sm text-gray-400">Verified Customer</p>
                    </div>
                </div>
            </div>

            {{-- Testimonial 2 --}}
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 transition">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    @endfor
                </div>
                <p class="text-gray-300 mb-6 italic">"Fit and comfort are amazing. I've ordered three more items since my first purchase!"</p>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gray-600"></div>
                    <div>
                        <p class="font-semibold text-white">Michael Chen</p>
                        <p class="text-sm text-gray-400">Verified Customer</p>
                    </div>
                </div>
            </div>

            {{-- Testimonial 3 --}}
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/10 transition">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    @endfor
                </div>
                <p class="text-gray-300 mb-6 italic">"Fast shipping and excellent customer service. Will definitely shop here again!"</p>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gray-500"></div>
                    <div>
                        <p class="font-semibold text-white">Emily Rodriguez</p>
                        <p class="text-sm text-gray-400">Verified Customer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Newsletter Subscription --}}
<div class="bg-gray-100 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Get 10% off your first order</h2>
            <p class="mt-4 text-lg text-gray-600">Subscribe to our newsletter for exclusive deals and updates</p>
            
            <form action="#" method="POST" class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                @csrf
                <input type="email" 
                       name="email" 
                       required 
                       placeholder="Enter your email" 
                       class="min-w-0 flex-auto rounded-md border-0 px-4 py-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-900 sm:text-sm sm:leading-6">
                <button type="submit" 
                        class="flex-none rounded-md bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                    Subscribe
                </button>
            </form>
            <p class="mt-4 text-sm text-gray-500">
                By subscribing, you agree to our Privacy Policy and consent to receive updates.
            </p>
        </div>
    </div>
</div>
@endsection
=======
<h1>Hello Lareval</h1>
>>>>>>> d7ba4bcab02cf90f68fc78d63acfc6807d81ac96
