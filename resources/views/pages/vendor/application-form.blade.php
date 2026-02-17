@extends('layouts.app')

@section('title', 'Become a Vendor - ' . config('app.name'))

@section('content')
<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Start Selling on {{ config('app.name') }}
        </h1>
        <p class="text-lg text-gray-600">
            Join thousands of successful vendors. Fill out the application form below and we'll review your business details.
        </p>
    </div>

    <!-- Application Form Card -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-slate-900 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Vendor Application Form</h2>
            <p class="text-sm text-gray-300 mt-1">Please provide accurate information for quick approval</p>
        </div>

        <form method="POST" action="{{ route('vendor.application.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Personal Information Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-input 
                            label="Full Name" 
                            name="full_name" 
                            type="text" 
                            placeholder="John Doe"
                            value="{{ old('full_name', auth()->user()->name) }}"
                            required 
                        />
                    </div>

                    <x-input 
                        label="Phone Number" 
                        name="phone" 
                        type="tel" 
                        placeholder="+1 (555) 123-4567"
                        required 
                    />

                    <x-input 
                        label="Business Contact Email (Optional)" 
                        name="email" 
                        type="email" 
                        placeholder="business@example.com"
                        value="{{ old('email') }}"
                        help="Leave blank to use your registered email: {{ auth()->user()->email }}"
                    />
                </div>
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-blue-600 mr-2 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Your registered email will be used as the primary contact</p>
                            <p class="text-blue-700">Only provide a different email if you want to use a separate business contact address.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Details Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Details</h3>
                <div class="grid grid-cols-1 gap-6">
                    <x-input 
                        label="Shop/Brand Name" 
                        name="shop_name" 
                        type="text" 
                        placeholder="Your Store Name"
                        required 
                    />

                    <div>
                        <label for="business_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Business Address <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="business_address" 
                            id="business_address" 
                            rows="2"
                            required
                            placeholder="123 Main Street, Suite 100"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 @error('business_address') border-red-500 @enderror"
                        >{{ old('business_address') }}</textarea>
                        @error('business_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <x-input 
                            label="City" 
                            name="city" 
                            type="text" 
                            placeholder="New York"
                            required 
                        />

                        <x-input 
                            label="State/Province" 
                            name="state" 
                            type="text" 
                            placeholder="NY"
                            required 
                        />

                        <x-input 
                            label="Pincode/ZIP" 
                            name="pincode" 
                            type="text" 
                            placeholder="10001"
                            required 
                        />
                    </div>
                </div>
            </div>

            <!-- Operational Details Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Operational Details</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="product_category" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Category <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="product_category" 
                            id="product_category" 
                            required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 @error('product_category') border-red-500 @enderror"
                        >
                            <option value="">Select a category</option>
                            <option value="Clothing">Clothing & Fashion</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Home & Garden">Home & Garden</option>
                            <option value="Sports">Sports & Outdoors</option>
                            <option value="Books">Books & Media</option>
                            <option value="Toys">Toys & Games</option>
                            <option value="Health">Health & Beauty</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('product_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-input 
                        label="Estimated Number of Products" 
                        name="estimated_products" 
                        type="number" 
                        placeholder="50"
                        min="1"
                        required 
                    />

                    <div class="sm:col-span-2">
                        <label for="pickup_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Pickup/Shipping Address <span class="text-gray-500">(Optional)</span>
                        </label>
                        <textarea 
                            name="pickup_address" 
                            id="pickup_address" 
                            rows="2"
                            placeholder="If different from business address"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                        >{{ old('pickup_address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Optional Information Section -->
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information (Optional)</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <x-input 
                        label="GST/Tax Number" 
                        name="gst_number" 
                        type="text" 
                        placeholder="22XXXXX1234X1X1"
                    />

                    <div>
                        <label for="id_proof" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Proof (PDF, JPG, PNG - Max 5MB)
                        </label>
                        <input 
                            type="file" 
                            name="id_proof" 
                            id="id_proof"
                            accept=".pdf,.jpg,.jpeg,.png"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800"
                        />
                        <p class="mt-1 text-xs text-gray-500">Upload government-issued ID or business registration</p>
                        @error('id_proof')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="bg-gray-50 p-4 rounded-md">
                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        name="terms" 
                        id="terms" 
                        required
                        class="mt-1 h-4 w-4 rounded border-gray-300 text-slate-900 focus:ring-slate-900"
                    >
                    <label for="terms" class="ml-3 text-sm text-gray-700">
                        I agree to the <a href="#" class="text-slate-900 font-medium hover:underline">Terms and Conditions</a> 
                        and <a href="#" class="text-slate-900 font-medium hover:underline">Vendor Agreement</a>. 
                        I understand that my application will be reviewed and I will be notified of the decision via email.
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                    Cancel
                </a>
                <x-button type="submit" variant="primary" size="lg">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Submit Application
                </x-button>
            </div>
        </form>
    </div>

    <!-- What Happens Next -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">What happens next?</h3>
        <ol class="space-y-2 text-sm text-blue-800">
            <li class="flex items-start">
                <span class="flex-shrink-0 font-bold mr-2">1.</span>
                <span>Your application will be reviewed by our team within 2-3 business days</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 font-bold mr-2">2.</span>
                <span>You'll receive an email notification about your application status</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 font-bold mr-2">3.</span>
                <span>If approved, you'll receive login credentials to access your vendor dashboard</span>
            </li>
            <li class="flex items-start">
                <span class="flex-shrink-0 font-bold mr-2">4.</span>
                <span>Start adding products and grow your business!</span>
            </li>
        </ol>
    </div>
</div>
@endsection
