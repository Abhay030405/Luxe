@extends('layouts.app')

@section('title', 'Check Application Status - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-md">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('vendor.application.create') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back
            </a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-white rounded-full mb-4">
                    <svg class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white text-center">Check Application Status</h1>
                <p class="text-blue-100 text-center mt-2">Enter your Application ID to view status</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm text-red-800 font-medium">{{ $errors->first('application_number') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('vendor.application.check.submit') }}" class="space-y-6">
                    @csrf

                    <!-- Application Number Input -->
                    <div>
                        <label for="application_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Application ID
                        </label>
                        <input 
                            type="text" 
                            name="application_number" 
                            id="application_number" 
                            placeholder="e.g., VA202602161234"
                            value="{{ old('application_number') }}"
                            required
                            class="block w-full px-4 py-3 text-lg font-mono border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('application_number') border-red-300 @enderror"
                        >
                        <p class="mt-2 text-xs text-gray-500">
                            Enter the Application ID you received when you submitted your application
                        </p>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Where to find your Application ID?</p>
                                <p>Your Application ID was displayed on the success page after submitting your application. It starts with "VA" followed by date and numbers.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                    >
                        Check Status
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Haven't applied yet?</span>
                    </div>
                </div>

                <!-- Apply Link -->
                <a href="{{ route('vendor.application.form') }}" class="block w-full text-center bg-slate-900 text-white font-semibold py-3 px-6 rounded-lg hover:bg-slate-800 transition-colors">
                    Apply for Vendor Account
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
