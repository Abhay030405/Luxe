<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Models\Product;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\View\View;

class VendorDashboardController extends Controller
{
    public function __construct(
        private readonly VendorService $vendorService
    ) {
        $this->middleware(['auth', 'vendor']);
    }

    /**
     * Display vendor dashboard.
     */
    public function index(): View
    {
        $vendor = $this->vendorService->getVendorByUserId(auth()->id());

        if (! $vendor) {
            return redirect()->route('home')->with('error', 'Vendor account not found.');
        }

        // Check if vendor is approved
        if ($vendor->status !== 'approved') {
            return view('pages.vendor.dashboard-pending', compact('vendor'));
        }

        // Get vendor statistics
        $stats = $this->vendorService->getVendorStatistics($vendor->id);

        // Get recent products (last 5)
        $recent_products = Product::where('vendor_id', $vendor->id)
            ->with('primaryImage')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages.vendor.dashboard', compact('vendor', 'stats', 'recent_products'));
    }

    /**
     * Show vendor profile edit form.
     */
    public function profile(): View
    {
        $vendor = $this->vendorService->getVendorByUserId(auth()->id());

        if (! $vendor) {
            return redirect()->route('home')->with('error', 'Vendor account not found.');
        }

        return view('pages.vendor.profile', compact('vendor'));
    }

    /**
     * Show vendor store page (public facing).
     */
    public function store(string $slug): View
    {
        $vendor = $this->vendorService->getVendorBySlug($slug);

        // Only show approved vendors' stores
        if ($vendor->status !== 'approved') {
            abort(404);
        }

        return view('pages.vendor.store', compact('vendor'));
    }
}
