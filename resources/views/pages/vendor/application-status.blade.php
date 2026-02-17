@extends('layouts.app')

@section('title', 'Application Status - ' . config('app.name'))

@section('content')
<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Vendor Application Status</h1>
        <p class="mt-2 text-gray-600">Track the status of your vendor application</p>
    </div>

    @if($application)
    <!-- Application Card -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-6">
        <!-- Status Header -->
        <div class="px-6 py-4 border-b border-gray-200 
            {{ $application->status === 'pending' ? 'bg-yellow-50' : '' }}
            {{ $application->status === 'approved' ? 'bg-green-50' : '' }}
            {{ $application->status === 'rejected' ? 'bg-red-50' : '' }}
        ">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Application #{{ $application->id }}</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                ">
                    {{ ucfirst($application->status) }}
                </span>
            </div>
        </div>

        <!-- Application Details -->
        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Shop Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->shop_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Product Category</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->product_category }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Applied On</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->created_at->format('M d, Y h:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Status Message -->
    @if($application->status === 'pending')
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <svg class="h-6 w-6 text-blue-600 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h3 class="text-base font-semibold text-blue-900 mb-2">Application Under Review</h3>
                <p class="text-sm text-blue-800 mb-3">
                    Your application is currently being reviewed by our team. We typically respond within 2-3 business days.
                </p>
                <p class="text-sm text-blue-700">
                    You will receive an email notification once a decision has been made.
                </p>
            </div>
        </div>
    </div>
    @elseif($application->status === 'approved')
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
        <div class="flex">
            <svg class="h-6 w-6 text-green-600 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1">
                <h3 class="text-base font-semibold text-green-900 mb-2">🎉 Congratulations! Application Approved</h3>
                <p class="text-sm text-green-800 mb-3">
                    Your vendor application has been approved. You should have received your login credentials via email.
                </p>
                @if($application->reviewed_at)
                <p class="text-xs text-green-700">
                    Approved on {{ $application->reviewed_at->format('F d, Y \a\t g:i A') }}
                </p>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->check() && auth()->user()->vendor)
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Next Steps</h3>
        <ul class="space-y-3 mb-6">
            <li class="flex items-start">
                <svg class="h-5 w-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm text-gray-700">Access your vendor dashboard to manage your store</span>
            </li>
            <li class="flex items-start">
                <svg class="h-5 w-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm text-gray-700">Start adding your products to the marketplace</span>
            </li>
            <li class="flex items-start">
                <svg class="h-5 w-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm text-gray-700">Set up your shop profile and branding</span>
            </li>
        </ul>
        <a href="{{ route('vendor.dashboard') }}">
            <x-button variant="primary" size="lg" class="w-full">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                </svg>
                Go to Vendor Dashboard
            </x-button>
        </a>
    </div>
    @endif
    @elseif($application->status === 'rejected')
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex">
            <svg class="h-6 w-6 text-red-600 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <div>
                <h3 class="text-base font-semibold text-red-900 mb-2">Application Rejected</h3>
                <p class="text-sm text-red-800 mb-3">
                    Unfortunately, your vendor application has been rejected.
                </p>
                @if($application->rejection_reason)
                <div class="bg-white border border-red-200 rounded-md p-3 mb-3">
                    <p class="text-sm font-medium text-gray-900 mb-1">Reason:</p>
                    <p class="text-sm text-gray-700">{{ $application->rejection_reason }}</p>
                </div>
                @endif
                @if($application->reviewed_at)
                <p class="text-xs text-red-700 mb-3">
                    Reviewed on {{ $application->reviewed_at->format('F d, Y \a\t g:i A') }}
                </p>
                @endif
                <p class="text-sm text-red-700">
                    If you believe this was a mistake or have additional information, please contact us at 
                    <a href="mailto:vendor@{{ config('app.domain', 'example.com') }}" class="font-medium underline">vendor@{{ config('app.domain', 'example.com') }}</a>
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Back Button -->
    <div class="mt-8 text-center">
        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
            ← Back to Home
        </a>
    </div>

    @else
    <!-- No Application Found -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 py-12">
        <div class="text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No Application Found</h3>
            <p class="mt-2 text-sm text-gray-500">
                You haven't submitted a vendor application yet.
            </p>
            <div class="mt-6">
                <a href="{{ route('vendor.application.create') }}">
                    <x-button variant="primary" size="lg">
                        Apply to Become a Vendor
                    </x-button>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
