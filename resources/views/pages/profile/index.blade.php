@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Profile</h1>

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Sidebar Navigation -->
        <aside class="lg:col-span-3">
            <nav class="space-y-1 sticky top-4" x-data="{ active: 'account' }">
                <button @click="active = 'account'" :class="active === 'account' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-4 py-3 rounded-lg font-medium text-sm transition">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Account Details
                </button>

                <button @click="active = 'address'" :class="active === 'address' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-4 py-3 rounded-lg font-medium text-sm transition">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    Addresses
                </button>

                <button @click="active = 'security'" :class="active === 'security' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-4 py-3 rounded-lg font-medium text-sm transition">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    Security
                </button>

                <button @click="active = 'wishlist'" :class="active === 'wishlist' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-4 py-3 rounded-lg font-medium text-sm transition">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                    Wishlist
                </button>

                <button @click="active = 'notifications'" :class="active === 'notifications' ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50'" class="w-full flex items-center px-4 py-3 rounded-lg font-medium text-sm transition">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    Notifications
                </button>
            </nav>
        </aside>

        <!-- Content Area -->
        <div class="mt-8 lg:mt-0 lg:col-span-9" x-data="{ active: 'account' }">
            <!-- Account Details -->
            <div x-show="active === 'account'">
                <x-card title="Account Details">
                    <form class="space-y-6">
                        <!-- Profile Photo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-4">Profile Photo</label>
                            <div class="flex items-center space-x-4">
                                <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                                    JD
                                </div>
                                <div class="flex-1">
                                    <x-button variant="outline" size="sm">Change Photo</x-button>
                                    <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF. Max size 2MB</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input label="First Name" name="first_name" value="John" required />
                            <x-input label="Last Name" name="last_name" value="Doe" required />
                        </div>

                        <x-input label="Email Address" type="email" name="email" value="john@example.com" required />
                        <x-input label="Phone Number" type="tel" name="phone" value="+1 234 567 8900" />

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Date of Birth</label>
                            <input type="date" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Gender</label>
                            <select class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600">
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                                <option>Prefer not to say</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <x-button variant="outline">Cancel</x-button>
                            <x-button variant="primary">Save Changes</x-button>
                        </div>
                    </form>
                </x-card>
            </div>

            <!-- Address Management -->
            <div x-show="active === 'address'">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Saved Addresses</h2>
                    <x-button variant="primary" size="sm">Add New Address</x-button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @for($i = 1; $i <= 2; $i++)
                    <x-card padding="false">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <x-badge color="{{ $i == 1 ? 'blue' : 'gray' }}">{{ $i == 1 ? 'Default' : 'Alternate' }}</x-badge>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                </button>
                            </div>

                            <div class="text-sm text-gray-600 space-y-1">
                                <p class="font-medium text-gray-900">John Doe</p>
                                <p>123 Main Street</p>
                                <p>New York, NY 10001</p>
                                <p>United States</p>
                                <p class="pt-2">Phone: +1 234 567 8900</p>
                            </div>
                        </div>

                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                            <x-button variant="outline" size="sm">Edit</x-button>
                            <button class="text-sm text-red-600 hover:text-red-700 font-medium">Delete</button>
                        </div>
                    </x-card>
                    @endfor
                </div>
            </div>

            <!-- Security Settings -->
            <div x-show="active === 'security'">
                <x-card title="Change Password" class="mb-6">
                    <form class="space-y-4">
                        <x-input label="Current Password" type="password" name="current_password" required />
                        <x-input label="New Password" type="password" name="new_password" required />
                        <x-input label="Confirm New Password" type="password" name="confirm_password" required />

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <x-button variant="outline">Cancel</x-button>
                            <x-button variant="primary">Update Password</x-button>
                        </div>
                    </form>
                </x-card>

                <x-card title="Two-Factor Authentication">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-medium text-gray-900 mb-1">Two-factor authentication</p>
                            <p class="text-sm text-gray-600">Add an extra layer of security to your account</p>
                        </div>
                        <x-badge color="gray">Disabled</x-badge>
                    </div>
                    <div class="mt-4">
                        <x-button variant="primary" size="sm">Enable 2FA</x-button>
                    </div>
                </x-card>
            </div>

            <!-- Wishlist -->
            <div x-show="active === 'wishlist'">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @for($i = 1; $i <= 6; $i++)
                    <x-card padding="false">
                        <div class="relative">
                            <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                                <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-purple-100"></div>
                            </div>
                            <button class="absolute top-3 right-3 p-2 rounded-full bg-white shadow-md hover:bg-red-50 text-red-600">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Product Name {{ $i }}</h3>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-lg font-bold text-gray-900">${{ rand(50, 300) }}.99</span>
                                <x-badge color="green">In Stock</x-badge>
                            </div>
                            <x-button variant="primary" class="w-full" size="sm">Add to Cart</x-button>
                        </div>
                    </x-card>
                    @endfor
                </div>
            </div>

            <!-- Notifications -->
            <div x-show="active === 'notifications'">
                <x-card title="Email Notifications">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <div>
                                <p class="font-medium text-gray-900">Order Updates</p>
                                <p class="text-sm text-gray-600">Receive notifications about your orders</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <div>
                                <p class="font-medium text-gray-900">Promotions</p>
                                <p class="text-sm text-gray-600">Receive promotional offers and discounts</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <div>
                                <p class="font-medium text-gray-900">Product Updates</p>
                                <p class="text-sm text-gray-600">Get notified about new products</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="font-medium text-gray-900">Newsletter</p>
                                <p class="text-sm text-gray-600">Weekly newsletter with tips and trends</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection
