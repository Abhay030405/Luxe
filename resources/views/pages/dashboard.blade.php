@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-3xl font-bold text-gray-900">
                    Welcome back, {{ Auth::user()->name }}! 👋
                </h2>
                <p class="mt-2 text-gray-600">
                    Here's what's happening with your account today.
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <a href="{{ route('profile.show', ['tab' => 'security']) }}">
                    <x-button variant="outline" class="mr-3">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </x-button>
                </a>
                <a href="{{ route('products.index') }}">
                    <x-button variant="primary">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        Browse Products
                    </x-button>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Orders -->
        <div class="overflow-hidden rounded-lg bg-white shadow border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                            <dd class="text-2xl font-bold text-gray-900">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="overflow-hidden rounded-lg bg-white shadow border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                            <dd class="text-2xl font-bold text-gray-900">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="overflow-hidden rounded-lg bg-white shadow border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Cart Items</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $cartCount ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wishlist -->
        <a href="{{ route('wishlist.index') }}" class="overflow-hidden rounded-lg bg-white shadow border border-gray-200 hover:shadow-lg transition">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Wishlist</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $wishlistCount ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Account Information -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Profile Card -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Account Information</h3>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Name</span>
                    <span class="text-sm text-gray-900">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Email</span>
                    <span class="text-sm text-gray-900">{{ Auth::user()->email }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Member Since</span>
                    <span class="text-sm text-gray-900">{{ Auth::user()->created_at->format('F j, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500">Email Verified</span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ Auth::user()->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ Auth::user()->email_verified_at ? 'Verified' : 'Not Verified' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="px-6 py-5 space-y-3">
                <a href="{{ route('orders.index') }}" class="flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition group">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 group-hover:bg-blue-200 transition">
                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900">View Orders</span>
                    </div>
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>

                <a href="{{ route('profile.show', ['tab' => 'account']) }}" class="flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition group">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 group-hover:bg-purple-200 transition">
                            <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900">Edit Profile</span>
                    </div>
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>

                <a href="{{ route('profile.show', ['tab' => 'address']) }}" class="flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition group">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 group-hover:bg-green-200 transition">
                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900">Manage Addresses</span>
                    </div>
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>

                <a href="{{ route('wishlist.index') }}" class="flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition group">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 group-hover:bg-red-200 transition">
                            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900">My Wishlist</span>
                    </div>
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
