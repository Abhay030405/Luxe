@extends('layouts.admin')

@section('title', 'Vendor Order Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.vendor-orders.index') }}" class="text-sm text-blue-600 hover:text-blue-800 mb-2 inline-block">
                ← Back to Vendor Orders
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Vendor Order #{{ $vendorOrder->vendor_order_number }}</h1>
            <p class="mt-1 text-sm text-gray-600">Placed on {{ $vendorOrder->created_at->format('F d, Y') }}</p>
        </div>
        <div>
            @php
                $statusClasses = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'accepted' => 'bg-blue-100 text-blue-800',
                    'packed' => 'bg-purple-100 text-purple-800',
                    'shipped' => 'bg-indigo-100 text-indigo-800',
                    'delivered' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    'rejected' => 'bg-gray-100 text-gray-800',
                ];
                $statusClass = $statusClasses[$vendorOrder->status->value] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClass }}">
                {{ $vendorOrder->status->label() }}
            </span>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($vendorOrder->items as $item)
                    <div class="p-6 flex items-start space-x-4">
                        @if($item->product->primary_image)
                        <img src="{{ asset('storage/' . $item->product->primary_image) }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-20 h-20 object-cover rounded-lg">
                        @else
                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif

                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                <span class="text-sm text-gray-600">×</span>
                                <span class="text-sm text-gray-600">${{ number_format($item->price, 2) }}</span>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">${{ number_format($item->total, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">${{ number_format($vendorOrder->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm border-t border-gray-200 pt-2">
                            <span class="text-gray-600">Commission ({{ number_format($vendorOrder->commission_rate, 1) }}%)</span>
                            <span class="font-medium text-red-600">-${{ number_format($vendorOrder->commission_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2">
                            <span class="text-gray-900">Vendor Earnings</span>
                            <span class="text-green-600">${{ number_format($vendorOrder->vendor_earnings, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            @if($vendorOrder->tracking_number)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipping Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Tracking Number</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $vendorOrder->tracking_number }}</p>
                    </div>
                    @if($vendorOrder->shipping_carrier)
                    <div>
                        <p class="text-sm text-gray-600">Carrier</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $vendorOrder->shipping_carrier }}</p>
                    </div>
                    @endif
                    @if($vendorOrder->shipped_at)
                    <div>
                        <p class="text-sm text-gray-600">Shipped Date</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $vendorOrder->shipped_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Timeline</h2>
                <div class="space-y-4">
                    @if($vendorOrder->delivered_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Delivered</p>
                            <p class="text-sm text-gray-500">{{ $vendorOrder->delivered_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($vendorOrder->shipped_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Shipped</p>
                            <p class="text-sm text-gray-500">{{ $vendorOrder->shipped_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($vendorOrder->packed_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Packed</p>
                            <p class="text-sm text-gray-500">{{ $vendorOrder->packed_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($vendorOrder->accepted_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Accepted</p>
                            <p class="text-sm text-gray-500">{{ $vendorOrder->accepted_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Order Created</p>
                            <p class="text-sm text-gray-500">{{ $vendorOrder->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    @if($vendorOrder->cancelled_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Cancelled</p>
                            <p class="text-sm text-gray-500">{{ $vendorOrder->cancelled_at->format('M d, Y H:i') }}</p>
                            @if($vendorOrder->cancellation_reason)
                            <p class="mt-1 text-sm text-red-600">Reason: {{ $vendorOrder->cancellation_reason }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Vendor Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Vendor Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Business Name</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $vendorOrder->vendor->business_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $vendorOrder->vendor->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $vendorOrder->vendor->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <a href="{{ route('admin.vendors.show', $vendorOrder->vendor->id) }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            View Vendor Profile →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $vendorOrder->order->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $vendorOrder->order->customer->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Customer Order #</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $vendorOrder->order->order_number }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <a href="{{ route('admin.orders.show', $vendorOrder->order->id) }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            View Customer Order →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            @if($vendorOrder->order->shippingAddress)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h2>
                <div class="text-sm text-gray-900 space-y-1">
                    <p>{{ $vendorOrder->order->shippingAddress->address_line1 }}</p>
                    @if($vendorOrder->order->shippingAddress->address_line2)
                    <p>{{ $vendorOrder->order->shippingAddress->address_line2 }}</p>
                    @endif
                    <p>{{ $vendorOrder->order->shippingAddress->city }}, {{ $vendorOrder->order->shippingAddress->state }} {{ $vendorOrder->order->shippingAddress->postal_code }}</p>
                    <p>{{ $vendorOrder->order->shippingAddress->country }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
