@extends('layouts.app')

@section('title', 'Order Details - Vendor Dashboard')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('vendor.orders.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Orders
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Order Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $vendorOrder->formattedVendorOrderNumber }}</h1>
                    <p class="text-gray-500 mt-1">Placed on {{ $vendorOrder->created_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
                <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $vendorOrder->status->badgeClass() }}">
                    {{ $vendorOrder->status->label() }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details - Main Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                </div>
                <div class="p-6">
                    @foreach($vendorOrder->items as $item)
                        <div class="flex items-center space-x-4 py-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                            @if($item->product && $item->product->primaryImageUrl)
                                <img src="{{ $item->product->primaryImageUrl }}" alt="{{ $item->product_name }}" class="w-16 h-16 rounded-lg object-cover">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</h3>
                                @if($item->product_sku)
                                    <p class="text-xs text-gray-500">SKU: {{ $item->product_sku }}</p>
                                @endif
                                <p class="text-sm text-gray-600 mt-1">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ $item->formattedSubtotal }}</p>
                                <p class="text-xs text-gray-500">{{ $item->formattedPrice }} each</p>
                            </div>
                        </div>
                    @endforeach

                    <!-- Order Totals -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold text-gray-900">{{ $vendorOrder->formattedSubtotal }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Platform Commission ({{ $vendorOrder->commission_rate }}%)</span>
                                <span class="font-semibold text-red-600">-{{ $vendorOrder->formattedCommission }}</span>
                            </div>
                            <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-200">
                                <span class="text-gray-900">Your Earnings</span>
                                <span class="text-green-600">{{ $vendorOrder->formattedEarnings }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            @if($vendorOrder->tracking_number)
                <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Information</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-600">Tracking Number:</span>
                            <span class="text-sm font-semibold text-gray-900 ml-2">{{ $vendorOrder->tracking_number }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Carrier:</span>
                            <span class="text-sm font-semibold text-gray-900 ml-2">{{ $vendorOrder->shipping_carrier }}</span>
                        </div>
                        @if($vendorOrder->shipped_at)
                            <div>
                                <span class="text-sm text-gray-600">Shipped on:</span>
                                <span class="text-sm font-semibold text-gray-900 ml-2">{{ $vendorOrder->shipped_at->format('F d, Y \a\t h:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Customer Information</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $vendorOrder->customer->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $vendorOrder->customer->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            @if($vendorOrder->shippingAddress)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Shipping Address</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-900">{{ $vendorOrder->shippingAddress['street_address'] ?? '' }}</p>
                        @if(isset($vendorOrder->shippingAddress['apartment_suite']))
                            <p class="text-sm text-gray-900">{{ $vendorOrder->shippingAddress['apartment_suite'] }}</p>
                        @endif
                        <p class="text-sm text-gray-900">
                            {{ $vendorOrder->shippingAddress['city'] ?? '' }}, 
                            {{ $vendorOrder->shippingAddress['state'] ?? '' }} 
                            {{ $vendorOrder->shippingAddress['postal_code'] ?? '' }}
                        </p>
                        <p class="text-sm text-gray-900">{{ $vendorOrder->shippingAddress['country'] ?? '' }}</p>
                        @if(isset($vendorOrder->shippingAddress['phone']))
                            <p class="text-sm text-gray-600 mt-2">Phone: {{ $vendorOrder->shippingAddress['phone'] }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Order Workflow Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Progress</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Pending -->
                        <div class="flex items-start">
                            <div class="shrink-0">
                                @if($vendorOrder->status->value === 'pending')
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-yellow-600 rounded-full animate-pulse"></div>
                                    </div>
                                @elseif(in_array($vendorOrder->status->value, ['accepted', 'packed', 'shipped', 'delivered']))
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">Order Placed</p>
                                <p class="text-xs text-gray-500">{{ $vendorOrder->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Accepted -->
                        <div class="flex items-start">
                            <div class="shrink-0">
                                @if($vendorOrder->status->value === 'accepted')
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full animate-pulse"></div>
                                    </div>
                                @elseif(in_array($vendorOrder->status->value, ['packed', 'shipped', 'delivered']))
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">Order Accepted</p>
                                <p class="text-xs text-gray-500">
                                    @if($vendorOrder->accepted_at)
                                        {{ $vendorOrder->accepted_at->format('M d, Y h:i A') }}
                                    @else
                                        Pending acceptance
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Packed -->
                        <div class="flex items-start">
                            <div class="shrink-0">
                                @if($vendorOrder->status->value === 'packed')
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-indigo-600 rounded-full animate-pulse"></div>
                                    </div>
                                @elseif(in_array($vendorOrder->status->value, ['shipped', 'delivered']))
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">Order Packed</p>
                                <p class="text-xs text-gray-500">
                                    @if($vendorOrder->packed_at)
                                        {{ $vendorOrder->packed_at->format('M d, Y h:i A') }}
                                    @else
                                        Not yet packed
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Shipped -->
                        <div class="flex items-start">
                            <div class="shrink-0">
                                @if($vendorOrder->status->value === 'shipped')
                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-purple-600 rounded-full animate-pulse"></div>
                                    </div>
                                @elseif($vendorOrder->status->value === 'delivered')
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">Order Shipped</p>
                                <p class="text-xs text-gray-500">
                                    @if($vendorOrder->shipped_at)
                                        {{ $vendorOrder->shipped_at->format('M d, Y h:i A') }}
                                    @else
                                        Not yet shipped
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Delivered -->
                        <div class="flex items-start">
                            <div class="shrink-0">
                                @if($vendorOrder->status->value === 'delivered')
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">Order Delivered</p>
                                <p class="text-xs text-gray-500">
                                    @if($vendorOrder->delivered_at)
                                        {{ $vendorOrder->delivered_at->format('M d, Y h:i A') }}
                                    @else
                                        Not yet delivered
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Actions</h2>
                    <p class="text-sm text-gray-600 mt-1">Current Status: <span class="font-medium">{{ $vendorOrder->status->label() }}</span></p>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Accept Order -->
                    @if($vendorOrder->canBeAccepted())
                        <form method="POST" action="{{ route('vendor.orders.accept', $vendorOrder->id) }}">
                            @csrf
                            <button type="submit" 
                                    style="background-color: #059669; color: white; font-size: 16px; font-weight: 600; padding: 14px 20px; border: 2px solid #047857;"
                                    class="w-full flex items-center justify-center rounded-lg hover:bg-green-700 transition shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span style="color: white;">✓ Accept Order</span>
                            </button>
                        </form>
                    @endif

                    <!-- Mark as Packed -->
                    @if($vendorOrder->canBePacked())
                        <form method="POST" action="{{ route('vendor.orders.pack', $vendorOrder->id) }}">
                            @csrf
                            <button type="submit" 
                                    style="background-color: #4f46e5; color: white; font-size: 16px; font-weight: 600; padding: 14px 20px; border: 2px solid #4338ca;"
                                    class="w-full flex items-center justify-center rounded-lg hover:bg-indigo-700 transition shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span style="color: white;">📦 Mark as Packed</span>
                            </button>
                        </form>
                    @endif

                    <!-- Ship Order -->
                    @if($vendorOrder->canBeShipped())
                        <button onclick="document.getElementById('shippingModal').classList.remove('hidden')" 
                                type="button"
                                style="background-color: #9333ea; color: white; font-size: 16px; font-weight: 600; padding: 14px 20px; border: 2px solid #7e22ce;"
                                class="w-full flex items-center justify-center rounded-lg hover:bg-purple-700 transition shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <span style="color: white;">🚚 Ship Order</span>
                        </button>
                    @endif

                    <!-- Mark as Delivered -->
                    @if($vendorOrder->canBeDelivered())
                        <form method="POST" action="{{ route('vendor.orders.deliver', $vendorOrder->id) }}">
                            @csrf
                            <button type="submit" 
                                    style="background-color: #059669; color: white; font-size: 16px; font-weight: 600; padding: 14px 20px; border: 2px solid #047857;"
                                    class="w-full flex items-center justify-center rounded-lg hover:bg-green-700 transition shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span style="color: white;">✓ Mark as Delivered</span>
                            </button>
                        </form>
                    @endif

                    <!-- Divider if any action buttons shown -->
                    @if($vendorOrder->canBeAccepted() || $vendorOrder->canBePacked() || $vendorOrder->canBeShipped() || $vendorOrder->canBeDelivered())
                        <div class="border-t-2 border-gray-300 my-4"></div>
                    @endif

                    <!-- Cancel Order -->
                    @if($vendorOrder->canBeCancelled())
                        <button onclick="document.getElementById('cancelModal').classList.remove('hidden')" 
                                type="button"
                                style="background-color: #dc2626; color: white; font-size: 16px; font-weight: 600; padding: 14px 20px; border: 2px solid #b91c1c;"
                                class="w-full flex items-center justify-center rounded-lg hover:bg-red-700 transition shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span style="color: white;">✕ Cancel Order</span>
                        </button>
                    @endif

                    <!-- Reject Order -->
                    @if($vendorOrder->status->value === 'pending')
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                                type="button"
                                style="background-color: #4b5563; color: white; font-size: 16px; font-weight: 600; padding: 14px 20px; border: 2px solid #374151;"
                                class="w-full flex items-center justify-center rounded-lg hover:bg-gray-700 transition shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                            <span style="color: white;">⊘ Reject Order</span>
                        </button>
                    @endif

                    <!-- No actions message -->
                    @if(!$vendorOrder->canBeAccepted() && !$vendorOrder->canBePacked() && !$vendorOrder->canBeShipped() && !$vendorOrder->canBeDelivered() && !$vendorOrder->canBeCancelled() && $vendorOrder->status->value !== 'pending')
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-600">No actions available for this order.</p>
                            <p class="text-xs text-gray-500 mt-1">Order status: {{ $vendorOrder->status->label() }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shipping Modal -->
<div id="shippingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Ship Order</h3>
        <form method="POST" action="{{ route('vendor.orders.ship', $vendorOrder->id) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number *</label>
                    <input type="text" name="tracking_number" required 
                           style="border: 2px solid #d1d5db; padding: 10px;"
                           class="w-full rounded-lg focus:ring-purple-600 focus:border-purple-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Carrier *</label>
                    <input type="text" name="shipping_carrier" required placeholder="e.g., FedEx, UPS, USPS"
                           style="border: 2px solid #d1d5db; padding: 10px;"
                           class="w-full rounded-lg focus:ring-purple-600 focus:border-purple-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (optional)</label>
                    <textarea name="notes" rows="3" 
                              style="border: 2px solid #d1d5db; padding: 10px;"
                              class="w-full rounded-lg focus:ring-purple-600 focus:border-purple-600"></textarea>
                </div>
            </div>
            <div class="flex space-x-3 mt-6">
                <button type="button" onclick="document.getElementById('shippingModal').classList.add('hidden')"
                        style="background-color: #e5e7eb; color: #1f2937; font-weight: 600; padding: 12px 16px;"
                        class="flex-1 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit" 
                        style="background-color: #9333ea; color: white; font-weight: 600; padding: 12px 16px;"
                        class="flex-1 rounded-lg hover:bg-purple-700 transition">
                    🚚 Ship Order
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Cancel Modal -->
<div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Cancel Order</h3>
        <form method="POST" action="{{ route('vendor.orders.cancel', $vendorOrder->id) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cancellation Reason *</label>
                    <textarea name="reason" rows="4" required placeholder="Please explain why you're cancelling this order..."
                              style="border: 2px solid #d1d5db; padding: 10px;"
                              class="w-full rounded-lg focus:ring-red-600 focus:border-red-600"></textarea>
                </div>
            </div>
            <div class="flex space-x-3 mt-6">
                <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden')"
                        style="background-color: #e5e7eb; color: #1f2937; font-weight: 600; padding: 12px 16px;"
                        class="flex-1 rounded-lg hover:bg-gray-300 transition">
                    Close
                </button>
                <button type="submit" 
                        style="background-color: #dc2626; color: white; font-weight: 600; padding: 12px 16px;"
                        class="flex-1 rounded-lg hover:bg-red-700 transition">
                    ✕ Cancel Order
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Reject Order</h3>
        <form method="POST" action="{{ route('vendor.orders.reject', $vendorOrder->id) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                    <textarea name="reason" rows="4" required placeholder="Please explain why you're rejecting this order..."
                              style="border: 2px solid #d1d5db; padding: 10px;"
                              class="w-full rounded-lg focus:ring-gray-600 focus:border-gray-600"></textarea>
                </div>
            </div>
            <div class="flex space-x-3 mt-6">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        style="background-color: #e5e7eb; color: #1f2937; font-weight: 600; padding: 12px 16px;"
                        class="flex-1 rounded-lg hover:bg-gray-300 transition">
                    Close
                </button>
                <button type="submit" 
                        style="background-color: #4b5563; color: white; font-weight: 600; padding: 12px 16px;"
                        class="flex-1 rounded-lg hover:bg-gray-700 transition">
                    ⊘ Reject Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
