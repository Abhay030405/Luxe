<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Vendor\Events\VendorApplicationSubmitted;
use App\Modules\Vendor\Models\VendorApplication;
use App\Modules\Vendor\Requests\SubmitVendorApplicationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VendorApplicationController extends Controller
{
    /**
     * Show vendor application landing page.
     */
    public function index(): View
    {
        return view('pages.vendor.application-landing');
    }

    /**
     * Show the vendor application form.
     */
    public function create(): View
    {
        $user = auth()->user();

        // Check if user already has a pending vendor application
        if ($user->hasPendingVendorApplication()) {
            return redirect()
                ->route('profile.show')
                ->with('info', 'You already have a pending vendor application. Please wait for admin review.');
        }

        // Check if user already has an approved vendor account
        if ($user->isApprovedVendor()) {
            return redirect()->route('vendor.dashboard');
        }

        // Check if user has a rejected application
        if ($user->hasVendorApplication() && $user->vendorApplication->status === 'rejected') {
            // Allow reapplication but show rejection reason
            $existingApplication = $user->vendorApplication;

            return view('pages.vendor.application-form', compact('existingApplication'));
        }

        return view('pages.vendor.application-form');
    }

    /**
     * Store a new vendor application.
     */
    public function store(SubmitVendorApplicationRequest $request): RedirectResponse
    {
        $user = auth()->user();

        // Check if user already has a pending application
        if ($user->hasPendingVendorApplication()) {
            return redirect()
                ->route('profile.show')
                ->with('error', 'You already have a pending vendor application.');
        }

        $data = $request->validated();

        // Automatically associate with logged-in user
        $data['user_id'] = $user->id;

        // Use logged-in user's email if no business email provided
        if (empty($data['email'])) {
            $data['email'] = $user->email;
        }

        // Handle ID proof upload if present
        if ($request->hasFile('id_proof')) {
            $data['id_proof'] = $request->file('id_proof')->store('vendor-applications/id-proofs', 'public');
        }

        // Check if user has a rejected application (reapplication)
        if ($user->hasVendorApplication() && $user->vendorApplication->status === 'rejected') {
            // Update the existing rejected application
            $application = $user->vendorApplication;
            $application->update([
                ...$data,
                'status' => 'pending',
                'rejection_reason' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]);
        } else {
            // Generate unique application number for new applications
            $data['application_number'] = $this->generateApplicationNumber();

            // Create new application
            $application = VendorApplication::create($data);
        }

        // Dispatch event for notification
        event(new VendorApplicationSubmitted($application));

        return redirect()
            ->route('profile.show')
            ->with('success', 'Your vendor application has been submitted successfully! We will review it and get back to you soon.');
    }

    /**
     * Generate a unique application number.
     */
    private function generateApplicationNumber(): string
    {
        do {
            $number = 'VA'.date('Ymd').rand(1000, 9999);
        } while (VendorApplication::where('application_number', $number)->exists());

        return $number;
    }

    /**
     * Show application success page.
     */
    public function success(string $application_number): View
    {
        $application = VendorApplication::where('application_number', $application_number)->firstOrFail();

        return view('pages.vendor.application-success', compact('application'));
    }

    /**
     * Show check status form.
     */
    public function checkStatusForm(): View
    {
        return view('pages.vendor.check-status');
    }

    /**
     * Show application status by application number.
     */
    public function checkStatus(): View|RedirectResponse
    {
        $user = auth()->user();

        // For authenticated users, show their own application
        if (! $user->hasVendorApplication()) {
            return redirect()
                ->route('profile.show')
                ->with('info', 'You have not submitted a vendor application yet.');
        }

        $application = $user->vendorApplication;

        return view('pages.vendor.application-status', compact('application'));
    }
}
