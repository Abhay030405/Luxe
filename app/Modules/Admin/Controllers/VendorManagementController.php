<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Vendor\Events\VendorApproved;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Vendor\Models\VendorApplication;
use App\Modules\Vendor\Requests\ApproveVendorRequest;
use App\Modules\Vendor\Requests\RejectVendorRequest;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VendorManagementController extends Controller
{
    public function __construct(
        private readonly VendorService $vendorService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display pending vendor applications.
     */
    public function applications(Request $request): View
    {
        $status = $request->input('status', 'pending');

        $applications = VendorApplication::with(['reviewer', 'vendor'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get application statistics for the stats cards
        $stats = [
            'pending' => VendorApplication::where('status', 'pending')->count(),
            'approved' => VendorApplication::where('status', 'approved')->count(),
            'rejected' => VendorApplication::where('status', 'rejected')->count(),
        ];

        return view('pages.admin.vendors.applications', compact('applications', 'status', 'stats'));
    }

    /**
     * Show specific vendor application details.
     */
    public function showApplication(int $id): View
    {
        $application = VendorApplication::with(['reviewer', 'vendor'])->findOrFail($id);

        return view('pages.admin.vendors.application-details', compact('application'));
    }

    /**
     * Approve a vendor application.
     */
    public function approveApplication(int $id, ApproveVendorRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $application = VendorApplication::findOrFail($id);

            if ($application->status !== 'pending') {
                return redirect()->back()->with('error', 'This application has already been processed.');
            }

            // Check if application is linked to an existing user
            if ($application->user_id) {
                // Update existing user's role to vendor
                $user = User::findOrFail($application->user_id);
                $user->update(['role' => 'vendor']);
            } else {
                // Create new user account (for old applications without user_id)
                $user = User::create([
                    'name' => $application->full_name,
                    'email' => $application->email,
                    'password' => Hash::make(Str::random(16)), // Temporary password
                    'role' => 'vendor',
                    'is_admin' => false,
                ]);
            }

            // Check if vendor profile already exists for this user (including soft-deleted)
            $vendor = Vendor::withTrashed()->where('user_id', $user->id)->first();

            // If vendor was soft-deleted, restore it
            if ($vendor && $vendor->trashed()) {
                $vendor->restore();
            }

            // Generate unique slug and business name
            $baseSlug = Str::slug($application->shop_name);
            $slug = $baseSlug;
            $businessName = $application->shop_name;
            $counter = 1;

            // Ensure slug is unique (exclude current vendor if updating)
            while (Vendor::where('slug', $slug)
                ->when($vendor, fn ($query) => $query->where('id', '!=', $vendor->id))
                ->exists()
            ) {
                $slug = $baseSlug.'-'.$counter++;
            }

            // Ensure business name is unique (exclude current vendor if updating)
            $nameCounter = 1;
            while (Vendor::where('business_name', $businessName)
                ->when($vendor, fn ($query) => $query->where('id', '!=', $vendor->id))
                ->exists()
            ) {
                $businessName = $application->shop_name.' '.$nameCounter++;
            }

            if ($vendor) {
                // Update existing vendor profile
                $vendor->update([
                    'business_name' => $businessName,
                    'slug' => $slug,
                    'email' => $application->email,
                    'phone' => $application->phone,
                    'address_line1' => $application->business_address,
                    'city' => $application->city,
                    'state' => $application->state,
                    'postal_code' => $application->pincode,
                    'tax_id' => $application->gst_number,
                    'status' => 'approved',
                    'commission_rate' => $request->input('commission_rate', 10.00),
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);
            } else {
                // Create new vendor profile
                $vendor = Vendor::create([
                    'user_id' => $user->id,
                    'business_name' => $businessName,
                    'slug' => $slug,
                    'description' => null,
                    'email' => $application->email,
                    'phone' => $application->phone,
                    'address_line1' => $application->business_address,
                    'city' => $application->city,
                    'state' => $application->state,
                    'postal_code' => $application->pincode,
                    'country' => 'USA',
                    'business_type' => 'individual',
                    'tax_id' => $application->gst_number,
                    'status' => 'approved',
                    'commission_rate' => $request->input('commission_rate', 10.00),
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);
            }

            // Update application
            $application->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'vendor_id' => $vendor->id,
            ]);

            // Dispatch approval event
            event(new VendorApproved($vendor));

            DB::commit();

            return redirect()
                ->route('admin.vendors.applications')
                ->with('success', 'Vendor application approved successfully! An activation email has been sent.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to approve application: '.$e->getMessage());
        }
    }

    /**
     * Reject a vendor application.
     */
    public function rejectApplication(int $id, RejectVendorRequest $request): RedirectResponse
    {
        $application = VendorApplication::findOrFail($id);

        if ($application->status !== 'pending') {
            return redirect()->back()->with('error', 'This application has already been processed.');
        }

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('admin.vendors.applications')
            ->with('success', 'Vendor application rejected.');
    }

    /**
     * Display all vendors.
     */
    public function index(Request $request): View
    {
        $filters = [
            'status' => $request->input('status'),
            'search' => $request->input('search'),
        ];

        $vendors = $this->vendorService->getPaginatedVendors($filters, 15);

        // Get vendor statistics for the stats cards
        $stats = [
            'total' => \App\Modules\Vendor\Models\Vendor::count(),
            'approved' => \App\Modules\Vendor\Models\Vendor::where('status', 'approved')->count(),
            'pending' => \App\Modules\Vendor\Models\Vendor::where('status', 'pending')->count(),
            'suspended' => \App\Modules\Vendor\Models\Vendor::where('status', 'suspended')->count(),
        ];

        return view('pages.admin.vendors.index', compact('vendors', 'filters', 'stats'));
    }

    /**
     * Show specific vendor details.
     */
    public function show(int $id): View
    {
        $vendor = $this->vendorService->getVendorById($id);
        $statistics = $this->vendorService->getVendorStatistics($id);

        return view('pages.admin.vendors.show', compact('vendor', 'statistics'));
    }

    /**
     * Suspend a vendor.
     */
    public function suspend(int $id): RedirectResponse
    {
        try {
            $this->vendorService->suspendVendor($id);

            return redirect()->back()->with('success', 'Vendor suspended successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to suspend vendor: '.$e->getMessage());
        }
    }

    /**
     * Reactivate a suspended vendor.
     */
    public function reactivate(int $id): RedirectResponse
    {
        try {
            $this->vendorService->reactivateVendor($id);

            return redirect()->back()->with('success', 'Vendor reactivated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reactivate vendor: '.$e->getMessage());
        }
    }
}
