@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
        <p class="mt-2 text-sm text-gray-600">Update your personal information</p>
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
            <x-card title="Edit Account Information">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input 
                            label="Full Name" 
                            name="name" 
                            type="text" 
                            required
                            :value="$profile->name"
                        />

                        <x-input 
                            label="Email Address" 
                            name="email" 
                            type="email" 
                            required
                            :value="$profile->email"
                        />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input 
                            label="Phone Number" 
                            name="phone" 
                            type="text"
                            :value="$profile->phone"
                        />

                        <x-input 
                            label="Date of Birth" 
                            name="date_of_birth" 
                            type="date"
                            :value="$profile->dateOfBirth"
                        />
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Gender</label>
                        <select name="gender" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600 sm:text-sm transition">
                            <option value="">Select gender</option>
                            <option value="male" {{ old('gender', $profile->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $profile->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $profile->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            <option value="prefer_not_to_say" {{ old('gender', $profile->gender) === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                        </select>
                        @error('gender')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-900 mb-2">Bio</label>
                        <textarea 
                            name="bio" 
                            id="bio" 
                            rows="4"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-600 focus:ring-blue-600 sm:text-sm transition"
                        >{{ old('bio', $profile->bio) }}</textarea>
                        @error('bio')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('profile.show') }}">
                            <x-button type="button" variant="outline">
                                Cancel
                            </x-button>
                        </a>
                        <x-button type="submit" variant="primary">
                            Save Changes
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</div>
@endsection
