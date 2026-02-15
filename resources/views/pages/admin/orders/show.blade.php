@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Orders
            </a>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->status->badgeClass() }}">
                {{ $order->status->label() }}
            </span>
        </div>
    </div>

    <!-- Order Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Order #{{ $order->order_number }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-600">Order Date</p>
                <p class="mt-1 font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <p class="mt-1 font-medium text-gray-900">{{ $order->status->label() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Amount</p>
                <p class="mt-1 font-medium text-gray-900">{{ currency($order->total_amount) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center space-x-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                            @if($item->product && $item->product->images->first())
                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="h-16 w-16 rounded-lg object-cover">
                            @else
                            <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                </svg>
                            </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                <p class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</p>
                                <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × {{ currency($item->unit_price) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">{{ currency($item->subtotal) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">{{ currency($order->subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium text-gray-900">{{ currency($order->tax) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping Fee</span>
                            <span class="font-medium text-gray-900">{{ currency($order->shipping_fee) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-900">Total</span>
                            <span class="text-gray-900">{{ currency($order->total_amount) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Notes -->
            @if($order->customer_notes)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Customer Notes</h3>
                <p class="text-gray-700">{{ $order->customer_notes }}</p>
            </div>
            @endif

            <!-- Admin Notes -->
            @if($order->admin_notes)
            <div class="bg-yellow-50 rounded-lg shadow-sm border border-yellow-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Admin Notes</h3>
                <p class="text-gray-700">{{ $order->admin_notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="font-medium text-gray-900">{{ $order->user->profile->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                @if($order->address_snapshot)
                <div class="text-gray-700">
                    <p>{{ $order->address_snapshot['address_line_1'] ?? '' }}</p>
                    @if(!empty($order->address_snapshot['address_line_2']))
                    <p>{{ $order->address_snapshot['address_line_2'] }}</p>
                    @endif
                    <p>{{ $order->address_snapshot['city'] ?? '' }}, {{ $order->address_snapshot['state'] ?? '' }} {{ $order->address_snapshot['postal_code'] ?? '' }}</p>
                    <p>{{ $order->address_snapshot['country'] ?? '' }}</p>
                </div>
                @else
                <p class="text-sm text-gray-500">No shipping address available</p>
                @endif
            </div>

            <!-- Update Order Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600">
                            @foreach(\App\Shared\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ $order->status === $status ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="3" 
                                  class="w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600"
                                  placeholder="Add notes about this status change..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                        Update Status
                    </button>
                </form>

                @if($order->canBeCancelled())
                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium"
                            onclick="return confirm('Are you sure you want to cancel this order?')">
                        Cancel Order
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
