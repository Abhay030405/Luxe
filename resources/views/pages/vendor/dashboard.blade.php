@extends('layouts.app')

@section('title', 'Vendor Dashboard - ' . config('app.name'))

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-gray-900">
                Welcome back, {{ auth()->user()->vendor->shop_name }}!
            </h1>
            <p class="mt-2 text-gray-600">
                Here's an overview of your vendor account
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('vendor.products.create') }}">
                <x-button variant="primary" size="lg">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add New Product
                </x-button>
            </a>
        </div>
    </div>

    <!-- Status Alert -->
    @if(auth()->user()->vendor->status === 'pending')
    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <div>
                <h3 class="text-sm font-medium text-yellow-800">Account Pending Activation</h3>
                <p class="mt-1 text-sm text-yellow-700">Your vendor account is still under review. You'll be notified once it's activated.</p>
            </div>
        </div>
    </div>
    @elseif(auth()->user()->vendor->status === 'suspended')
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-red-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <div>
                <h3 class="text-sm font-medium text-red-800">Account Suspended</h3>
                <p class="mt-1 text-sm text-red-700">Your vendor account has been suspended. Please contact support for more information.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Products -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <div class="ml-4 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Products</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['total_products'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 sm:px-6 py-3">
                <a href="{{ route('vendor.products.index') }}" class="text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-800">
                    View all products →
                </a>
            </div>
        </div>

        <!-- Active Products -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Active Products</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['active_products'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 sm:px-6 py-3">
                <a href="{{ route('vendor.products.index', ['status' => 'active']) }}" class="text-xs sm:text-sm font-medium text-green-600 hover:text-green-800">
                    View active →
                </a>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    <div class="ml-4 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 sm:px-6 py-3">
                <span class="text-xs sm:text-sm text-gray-500">All time</span>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4 sm:ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Earnings</dt>
                            <dd class="text-2xl sm:text-3xl font-bold text-gray-900">₹{{ number_format($stats['total_earnings'] ?? 0, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 sm:px-6 py-3">
                <span class="text-xs sm:text-sm text-gray-500">After commission</span>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2">
        <!-- Recent Products -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Recent Products</h2>
            </div>
            <div class="p-4 sm:p-6">
                @if(count($recent_products ?? []) > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($recent_products as $product)
                    <li class="py-3 sm:py-4 flex items-center">
                        @if($product->primaryImage)
                        <img src="{{ $product->primaryImage->image_url }}" alt="{{ $product->name }}" class="h-10 w-10 sm:h-12 sm:w-12 rounded-md object-cover shrink-0">
                        @else
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-md bg-gray-200 flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        @endif
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                            <p class="text-xs sm:text-sm text-gray-500">₹{{ number_format($product->price, 2) }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    <p class="mt-2 text-xs sm:text-sm text-gray-500">No products yet</p>
                    <a href="{{ route('vendor.products.create') }}" class="mt-3 sm:mt-4 inline-flex items-center text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-800">
                        Add your first product →
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Account Information</h2>
            </div>
            <div class="p-4 sm:p-6">
                <dl class="space-y-3 sm:space-y-4">
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Shop Name</dt>
                        <dd class="mt-1 text-xs sm:text-sm text-gray-900">{{ auth()->user()->vendor->shop_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Business Email</dt>
                        <dd class="mt-1 text-xs sm:text-sm text-gray-900 wrap-break-word">{{ auth()->user()->vendor->business_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Contact Phone</dt>
                        <dd class="mt-1 text-xs sm:text-sm text-gray-900">{{ auth()->user()->vendor->business_phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Account Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 rounded-full text-xs font-medium 
                                {{ auth()->user()->vendor->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ auth()->user()->vendor->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ auth()->user()->vendor->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                                {{ ucfirst(auth()->user()->vendor->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Commission Rate</dt>
                        <dd class="mt-1 text-xs sm:text-sm text-gray-900">{{ auth()->user()->vendor->commission_rate }}%</dd>
                    </div>
                    <div>
                        <dt class="text-xs sm:text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="mt-1 text-xs sm:text-sm text-gray-900">{{ auth()->user()->vendor->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
