@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="mt-2 text-sm text-gray-600">Manage your personal information and account settings</p>
    </div>

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Sidebar Navigation -->
        <aside class="lg:col-span-3 mb-8 lg:mb-0">
            <nav class="space-y-1 sticky top-4">
                <a href="{{ route('profile.show') }}" class="bg-blue-50 text-blue-600 flex items-center px-4 py-3 rounded-lg font-medium text-sm transition">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Account Details
                </a>

                <a href="{{ route('addresses.index') }}" class="text-gray-700 hover:bg-gray-50 flex items-center px-4 py-3 rounded-lg font-medium text-sm transition">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    Addresses
                </a>

                <a href="{{ route('profile.password.edit') }}" class="text-gray-700 hover:bg-gray-50 flex items-center px-4 py-3 rounded-lg font-medium text-sm transition">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    Security
                </a>
            </nav>
        </aside>

        <!-- Content Area -->
        <div class="lg:col-span-9">
            <x-card title="Account Information">
                <div class="space-y-6">
                    <!-- Profile Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $profile->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $profile->email }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $profile->phone ?? 'Not provided' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Gender</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $profile->gender ? ucfirst(str_replace('_', ' ', $profile->gender)) : 'Not provided' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $profile->dateOfBirth ?? 'Not provided' }}</dd>
                        </div>
                    </div>

                    @if($profile->bio)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bio</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $profile->bio }}</dd>
                    </div>
                    @endif

                    <!-- Action Button -->
                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('profile.edit') }}">
                            <x-button variant="primary">
                                Edit Profile
                            </x-button>
                        </a>
                    </div>
                </div>
            </x-card>

            <!-- Vendor Account Section -->
            @if(auth()->user()->isApprovedVendor())
            <!-- Approved Vendor - Show Dashboard Access -->
            <x-card title="Vendor Account" class="mt-6">
                <div class="py-4">
                    <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6 mb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-xl font-bold text-green-900 mb-2">🎉 Congratulations! You're Now a Vendor</h3>
                                <p class="text-base text-green-800 mb-4">
                                    Your vendor application has been approved. You can now start managing your products and orders through the vendor dashboard.
                                </p>
                                @if(auth()->user()->vendor)
                                <div class="bg-white border border-green-200 rounded-lg p-4 mb-4">
                                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <dt class="font-medium text-gray-700">Business Name:</dt>
                                            <dd class="text-gray-900">{{ auth()->user()->vendor->business_name }}</dd>
                                        </div>
                                        <div>
                                            <dt class="font-medium text-gray-700">Commission Rate:</dt>
                                            <dd class="text-gray-900">{{ auth()->user()->vendor->commission_rate }}%</dd>
                                        </div>
                                        <div>
                                            <dt class="font-medium text-gray-700">Status:</dt>
                                            <dd>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="font-medium text-gray-700">Approved On:</dt>
                                            <dd class="text-gray-900">{{ auth()->user()->vendor->approved_at->format('M d, Y') }}</dd>
                                        </div>
                                    </dl>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('vendor.dashboard') }}">
                            <x-button variant="primary" size="lg">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                                Go to Vendor Dashboard
                            </x-button>
                        </a>
                        <a href="{{ route('vendor.products.index') }}">
                            <x-button variant="outline" size="lg">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                                Manage Products
                            </x-button>
                        </a>
                    </div>
                </div>
            </x-card>
            @elseif(auth()->user()->hasVendorApplication())
            <!-- Has Application - Show Status -->
            <x-card title="Business Account" class="mt-6">
                @php
                    $application = auth()->user()->vendorApplication;
                @endphp
                
                @if($application->status === 'pending')
                <div class="py-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex">
                            <svg class="h-6 w-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-yellow-900 mb-1">Application Under Review</h3>
                                <p class="text-sm text-yellow-800 mb-2">Your vendor application is currently being reviewed by our team.</p>
                                <p class="text-xs text-yellow-700">
                                    <strong>Application ID:</strong> {{ $application->application_number }}<br>
                                    <strong>Submitted:</strong> {{ $application->created_at->format('F d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('vendor.application.status') }}">
                        <x-button variant="outline">
                            View Application Details
                        </x-button>
                    </a>
                </div>
                @elseif($application->status === 'rejected')
                <div class="py-4">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <div class="flex">
                            <svg class="h-6 w-6 text-red-600 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-red-900 mb-1">Application Rejected</h3>
                                <p class="text-sm text-red-800 mb-2">Unfortunately, your vendor application was not approved.</p>
                                @if($application->rejection_reason)
                                <div class="bg-white border border-red-200 rounded p-2 mb-2">
                                    <p class="text-xs font-medium text-gray-900 mb-1">Reason:</p>
                                    <p class="text-xs text-gray-700">{{ $application->rejection_reason }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('vendor.application.status') }}">
                            <x-button variant="outline">
                                View Details
                            </x-button>
                        </a>
                        <a href="{{ route('vendor.application.form') }}">
                            <x-button variant="primary">
                                Apply Again
                            </x-button>
                        </a>
                    </div>
                </div>
                @endif
            </x-card>
            @else
            <!-- No Application Yet - Show Apply Button -->
            <x-card title="Business Account" class="mt-6">
                <div class="text-center py-6">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 mb-4">
                        <svg class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Become a Vendor</h3>
                    <p class="text-sm text-gray-600 mb-6 max-w-md mx-auto">
                        Start selling your products on our platform. Apply to convert your account to a business account and reach thousands of customers.
                    </p>
                    <a href="{{ route('vendor.application.form') }}">
                        <x-button variant="primary" size="lg">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                            </svg>
                            Convert to Business Account
                        </x-button>
                    </a>
                </div>
            </x-card>
            @endif
        </div>
    </div>
</div>
@endsection
