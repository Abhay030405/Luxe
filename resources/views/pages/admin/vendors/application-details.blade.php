@extends('layouts.admin')

@section('title', 'Application Details - Admin')

@section('content')
<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.vendors.applications') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Applications
        </a>
        <h1 class="text-3xl font-bold text-gray-900 mt-2">Vendor Application Details</h1>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium 
            {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
            {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
        ">
            {{ ucfirst($application->status) }}
        </span>
    </div>

    <!-- Personal Information -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Personal Information</h2>
        </div>
        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->full_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <a href="mailto:{{ $application->email }}" class="text-blue-600 hover:text-blue-800">{{ $application->email }}</a>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <a href="tel:{{ $application->phone }}" class="text-blue-600 hover:text-blue-800">{{ $application->phone }}</a>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Application Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->created_at->format('F d, Y \a\t g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Business Details -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Business Details</h2>
        </div>
        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Shop/Brand Name</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">{{ $application->shop_name }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Business Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->business_address }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">City</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->city }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">State/Province</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->state }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Pincode/ZIP</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->pincode }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Operational Details -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Operational Details</h2>
        </div>
        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Product Category</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->product_category }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Estimated Products</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->estimated_products }}</dd>
                </div>
                @if($application->pickup_address)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Pickup/Shipping Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->pickup_address }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Additional Information -->
    @if($application->gst_number || $application->id_proof_path)
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Additional Information</h2>
        </div>
        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                @if($application->gst_number)
                <div>
                    <dt class="text-sm font-medium text-gray-500">GST/Tax Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $application->gst_number }}</dd>
                </div>
                @endif
                @if($application->id_proof_path)
                <div>
                    <dt class="text-sm font-medium text-gray-500">ID Proof Document</dt>
                    <dd class="mt-1">
                        <a href="{{ Storage::url($application->id_proof_path) }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            View Document
                        </a>
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
    @endif

    <!-- Review Information -->
    @if($application->reviewed_at)
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Review Information</h2>
        </div>
        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Reviewed By</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->reviewer->name ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Review Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->reviewed_at->format('F d, Y \a\t g:i A') }}</dd>
                </div>
                @if($application->rejection_reason)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Rejection Reason</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $application->rejection_reason }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
    @endif

    <!-- Actions -->
    @if($application->status === 'pending')
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-5">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Review Actions</h3>
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Approve Form -->
                <form method="POST" action="{{ route('admin.vendors.applications.approve', $application->id) }}" class="flex-1">
                    @csrf
                    <x-button type="submit" variant="primary" size="lg" class="w-full">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Approve Application
                    </x-button>
                </form>

                <!-- Reject Button (opens modal) -->
                <x-button 
                    type="button" 
                    variant="danger" 
                    size="lg" 
                    class="flex-1"
                    onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                >
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reject Application
                </x-button>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Application</h3>
                <form method="POST" action="{{ route('admin.vendors.applications.reject', $application->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="rejection_reason" 
                            id="rejection_reason" 
                            rows="4"
                            required
                            placeholder="Please provide a reason for rejection..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                        ></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button 
                            type="button" 
                            onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
                        >
                            Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
