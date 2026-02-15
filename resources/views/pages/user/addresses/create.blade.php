@extends('layouts.app')

@section('title', 'Add New Address')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Checkout Notice -->
    @if($redirectTo === 'checkout')
        <div class="mb-6 rounded-lg bg-blue-50 p-4 border border-blue-200">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="ml-3 text-sm font-medium text-blue-800">You're adding an address for checkout. After saving, you'll be redirected to complete your order.</p>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Add New Address</h1>
        <p class="mt-2 text-sm text-gray-600">Enter your delivery address information</p>
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
            <x-card title="Address Information">
                <form method="POST" action="{{ route('addresses.store') }}" class="space-y-6">
                    @csrf

                    <!-- Hidden field for redirect_to -->
                    @if($redirectTo)
                        <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">
                    @endif

                    <!-- Contact Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input 
                            label="Full Name" 
                            name="full_name" 
                            type="text" 
                            required
                        />

                        <x-input 
                            label="Phone Number" 
                            name="phone" 
                            type="text" 
                            required
                        />
                    </div>

                    <!-- Address Lines -->
                    <x-input 
                        label="Address Line 1" 
                        name="address_line_1" 
                        type="text" 
                        required
                    />

                    <x-input 
                        label="Address Line 2 (Optional)" 
                        name="address_line_2" 
                        type="text"
                    />

                    <!-- Location -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-input 
                            label="City" 
                            name="city" 
                            type="text" 
                            required
                        />

                        <x-input 
                            label="State/Province" 
                            name="state" 
                            type="text" 
                            required
                        />

                        <x-input 
                            label="Postal Code" 
                            name="postal_code" 
                            type="text" 
                            required
                        />
                    </div>

                    <!-- Country -->
                    <x-input 
                        label="Country" 
                        name="country" 
                        type="text" 
                        required
                        value="USA"
                    />

                    <!-- Address Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">
                            Address Type <span class="text-red-600">*</span>
                        </label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="address_type" value="home" checked class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-600">
                                <span class="ml-2 text-sm text-gray-700">Home</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="address_type" value="work" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-600">
                                <span class="ml-2 text-sm text-gray-700">Work</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="address_type" value="other" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-600">
                                <span class="ml-2 text-sm text-gray-700">Other</span>
                            </label>
                        </div>
                        @error('address_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Set as Default -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_default" id="is_default" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-600">
                        <label for="is_default" class="ml-2 text-sm text-gray-700">Set as default address</label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('addresses.index') }}">
                            <x-button type="button" variant="outline">
                                Cancel
                            </x-button>
                        </a>
                        <x-button type="submit" variant="primary">
                            Save Address
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</div>
@endsection
