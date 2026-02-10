@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-purple-600 to-blue-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="relative py-24 sm:py-32 lg:py-40">
            <div class="mx-auto max-w-2xl text-center">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">
                    Welcome to Your E-Commerce Store
                </h1>
                <p class="mt-6 text-lg leading-8 text-gray-100">
                    Discover amazing products at incredible prices. Shop the latest trends and have them delivered to your door.
                </p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('register') }}" class="rounded-md bg-white px-6 py-3 text-base font-semibold text-blue-600 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transform hover:-translate-y-0.5 transition">
                        Get started
                    </a>
                    <a href="#features" class="text-base font-semibold leading-7 text-white hover:text-gray-100 transition">
                        Learn more <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Decorative background -->
    <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
        <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-blue-400 to-purple-300 opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"></div>
    </div>
</div>

<!-- Features Section -->
<div id="features" class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Why Choose Us?</h2>
            <p class="mt-4 text-lg text-gray-600">
                Everything you need for an amazing shopping experience
            </p>
        </div>

        <div class="mx-auto mt-16 max-w-7xl">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Feature 1 -->
                <div class="relative p-8 bg-gray-50 rounded-2xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white mb-6 group-hover:scale-110 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Fast Delivery</h3>
                    <p class="text-sm text-gray-600">Get your orders delivered quickly to your doorstep with our express shipping.</p>
                </div>

                <!-- Feature 2 -->
                <div class="relative p-8 bg-gray-50 rounded-2xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-600 text-white mb-6 group-hover:scale-110 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Secure Payment</h3>
                    <p class="text-sm text-gray-600">Shop with confidence using our secure payment gateway and encryption.</p>
                </div>

                <!-- Feature 3 -->
                <div class="relative p-8 bg-gray-50 rounded-2xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-600 text-white mb-6 group-hover:scale-110 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Easy Returns</h3>
                    <p class="text-sm text-gray-600">Not satisfied? Return your order within 30 days for a full refund.</p>
                </div>

                <!-- Feature 4 -->
                <div class="relative p-8 bg-gray-50 rounded-2xl border border-gray-200 hover:border-blue-300 hover:shadow-lg transition group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-600 text-white mb-6 group-hover:scale-110 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-sm text-gray-600">Our customer support team is always here to help you with any questions.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-blue-600">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                Ready to start shopping?
            </h2>
            <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-blue-100">
                Create your account today and get access to exclusive deals and offers.
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="{{ route('register') }}" class="rounded-md bg-white px-6 py-3 text-base font-semibold text-blue-600 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transform hover:-translate-y-0.5 transition">
                    Sign up now
                </a>
                <a href="{{ route('login') }}" class="text-base font-semibold leading-7 text-white hover:text-gray-100 transition">
                    Already have an account? <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection