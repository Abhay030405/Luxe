@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="mx-auto max-w-5xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Site Settings</h1>
        <p class="mt-2 text-gray-600">Manage your website configuration, footer information, and social links</p>
    </div>

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

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- General Settings -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">General Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Basic information about your website</p>
            </div>
            <div class="px-8 py-8 space-y-6">
                <div>
                    <label for="site_name" class="block text-sm font-semibold text-gray-900 mb-2">
                        Website Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="site_name" 
                        id="site_name" 
                        value="{{ old('site_name', $settings['site_name'] ?? 'Luxe Fashion') }}"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('site_name') border-red-300 @enderror"
                        required
                    >
                    @error('site_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_tagline" class="block text-sm font-semibold text-gray-900 mb-2">
                        Tagline
                    </label>
                    <input 
                        type="text" 
                        name="site_tagline" 
                        id="site_tagline" 
                        value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('site_tagline') border-red-300 @enderror"
                        placeholder="Your store's tagline"
                    >
                    @error('site_tagline')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Currency Settings -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Currency Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Choose your store's currency</p>
            </div>
            <div class="px-8 py-8">
                <div>
                    <label for="currency" class="block text-sm font-semibold text-gray-900 mb-2">
                        Select Currency <span class="text-red-500">*</span>
                    </label>
                    <select name="currency" id="currency" class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="INR|₹" {{ (($settings['currency_code'] ?? 'INR') === 'INR') ? 'selected' : '' }}>INR - ₹ (Indian Rupee)</option>
                        <option value="USD|$" {{ (($settings['currency_code'] ?? '') === 'USD') ? 'selected' : '' }}>USD - $ (US Dollar)</option>
                        <option value="EUR|€" {{ (($settings['currency_code'] ?? '') === 'EUR') ? 'selected' : '' }}>EUR - € (Euro)</option>
                        <option value="GBP|£" {{ (($settings['currency_code'] ?? '') === 'GBP') ? 'selected' : '' }}>GBP - £ (British Pound)</option>
                        <option value="AUD|A$" {{ (($settings['currency_code'] ?? '') === 'AUD') ? 'selected' : '' }}>AUD - A$ (Australian Dollar)</option>
                        <option value="CAD|C$" {{ (($settings['currency_code'] ?? '') === 'CAD') ? 'selected' : '' }}>CAD - C$ (Canadian Dollar)</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">This currency will be displayed throughout your store</p>
                </div>
            </div>
        </div>

        <!-- Social Media Links -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Social Media Links</h2>
                <p class="text-sm text-gray-600 mt-1">Connect your social media profiles</p>
            </div>
            <div class="px-8 py-8 space-y-6">
                <div>
                    <label for="social_facebook" class="block text-sm font-semibold text-gray-900 mb-2">
                        <svg class="inline h-5 w-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Facebook URL
                    </label>
                    <input 
                        type="url" 
                        name="social_facebook" 
                        id="social_facebook" 
                        value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="https://facebook.com/yourpage"
                    >
                </div>

                <div>
                    <label for="social_instagram" class="block text-sm font-semibold text-gray-900 mb-2">
                        <svg class="inline h-5 w-5 text-pink-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        Instagram URL
                    </label>
                    <input 
                        type="url" 
                        name="social_instagram" 
                        id="social_instagram" 
                        value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="https://instagram.com/yourprofile"
                    >
                </div>

                <div>
                    <label for="social_twitter" class="block text-sm font-semibold text-gray-900 mb-2">
                        <svg class="inline h-5 w-5 text-sky-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                        Twitter URL
                    </label>
                    <input 
                        type="url" 
                        name="social_twitter" 
                        id="social_twitter" 
                        value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="https://twitter.com/yourhandle"
                    >
                </div>
            </div>
        </div>

        <!-- Quick Links Content -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Quick Links</h2>
                <p class="text-sm text-gray-600 mt-1">Manage content for footer quick links</p>
            </div>
            <div class="px-8 py-8 space-y-6">
                <div>
                    <label for="link_about_us" class="block text-sm font-semibold text-gray-900 mb-2">
                        About Us Content
                    </label>
                    <textarea 
                        name="link_about_us" 
                        id="link_about_us" 
                        rows="5"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Write about your company, mission, and values..."
                    >{{ old('link_about_us', $settings['link_about_us'] ?? '') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">This will be displayed on the About Us page</p>
                </div>

                <div>
                    <label for="link_contact" class="block text-sm font-semibold text-gray-900 mb-2">
                        Contact Information
                    </label>
                    <textarea 
                        name="link_contact" 
                        id="link_contact" 
                        rows="5"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Add contact details, business hours, etc..."
                    >{{ old('link_contact', $settings['link_contact'] ?? '') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">Displayed on the Contact page</p>
                </div>

                <div>
                    <label for="link_faqs" class="block text-sm font-semibold text-gray-900 mb-2">
                        FAQs Content
                    </label>
                    <textarea 
                        name="link_faqs" 
                        id="link_faqs" 
                        rows="5"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Add frequently asked questions and answers..."
                    >{{ old('link_faqs', $settings['link_faqs'] ?? '') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">Common customer questions and answers</p>
                </div>

                <div>
                    <label for="link_return_policy" class="block text-sm font-semibold text-gray-900 mb-2">
                        Return Policy
                    </label>
                    <textarea 
                        name="link_return_policy" 
                        id="link_return_policy" 
                        rows="5"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Explain your return and refund policy..."
                    >{{ old('link_return_policy', $settings['link_return_policy'] ?? '') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">Your store's return and refund policy</p>
                </div>
            </div>
        </div>

        <!-- Footer Settings -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Footer Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Contact information displayed in the footer</p>
            </div>
            <div class="px-8 py-8 space-y-6">
                <div>
                    <label for="footer_about" class="block text-sm font-semibold text-gray-900 mb-2">
                        About Text
                    </label>
                    <textarea 
                        name="footer_about" 
                        id="footer_about" 
                        rows="3"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Brief description of your store"
                    >{{ old('footer_about', $settings['footer_about'] ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="footer_email" class="block text-sm font-semibold text-gray-900 mb-2">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            name="footer_email" 
                            id="footer_email" 
                            value="{{ old('footer_email', $settings['footer_email'] ?? '') }}"
                            class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="support@example.com"
                        >
                    </div>

                    <div>
                        <label for="footer_phone" class="block text-sm font-semibold text-gray-900 mb-2">
                            Phone Number
                        </label>
                        <input 
                            type="text" 
                            name="footer_phone" 
                            id="footer_phone" 
                            value="{{ old('footer_phone', $settings['footer_phone'] ?? '') }}"
                            class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="+91 98765 43210"
                        >
                    </div>
                </div>

                <div>
                    <label for="footer_address" class="block text-sm font-semibold text-gray-900 mb-2">
                        Address
                    </label>
                    <input 
                        type="text" 
                        name="footer_address" 
                        id="footer_address" 
                        value="{{ old('footer_address', $settings['footer_address'] ?? '') }}"
                        class="block w-full px-4 py-3 text-base rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="City, State, Country"
                    >
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end space-x-4 pb-8">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                <svg class="inline h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Save All Settings
            </button>
        </div>
    </form>
</div>
@endsection
