@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

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

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Addresses</h1>
                <p class="mt-2 text-sm text-gray-600">Manage your delivery addresses</p>
            </div>
            <a href="{{ route('addresses.create') }}">
                <x-button variant="primary">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add New Address
                </x-button>
            </a>
        </div>
    </div>

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Sidebar Navigation -->
        <aside class="lg:col-span-3 mb-8 lg:mb-0">
            <nav class="space-y-1 sticky top-4">
                <a href="{{ route('profile.show') }}" class="text-gray-700 hover:bg-gray-50 flex items-center px-4 py-3 rounded-lg font-medium text-sm transition">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Account Details
                </a>

                <a href="{{ route('addresses.index') }}" class="bg-blue-50 text-blue-600 flex items-center px-4 py-3 rounded-lg font-medium text-sm transition">
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
            @if($addresses->isEmpty())
                <x-empty-state 
                    title="No addresses found"
                    description="You haven't added any delivery addresses yet. Add your first address to get started."
                    actionText="Add Address"
                    :actionUrl="route('addresses.create')"
                />
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($addresses as $address)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition {{ $address->isDefault ? 'ring-2 ring-blue-600' : '' }}">
                            <div class="p-6">
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $address->fullName }}</h3>
                                        @if($address->isDefault)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($address->addressType) }}
                                    </span>
                                </div>

                                <!-- Address Details -->
                                <div class="space-y-1 text-sm text-gray-600 mb-4">
                                    <p>{{ $address->addressLine1 }}</p>
                                    @if($address->addressLine2)
                                        <p>{{ $address->addressLine2 }}</p>
                                    @endif
                                    <p>{{ $address->city }}, {{ $address->state }} {{ $address->postalCode }}</p>
                                    <p>{{ $address->country }}</p>
                                    <p class="pt-2 font-medium text-gray-900">{{ $address->phone }}</p>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                                    @if(!$address->isDefault)
                                        <form method="POST" action="{{ route('addresses.set-default', $address->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sm font-medium text-blue-600 hover:text-blue-500 transition">
                                                Set as Default
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('addresses.edit', $address->id) }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 transition">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('addresses.destroy', $address->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500 transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
