<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Services\VendorOrderService;
use App\Modules\Vendor\Repositories\VendorRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorOrderController extends Controller
{
    public function __construct(
        private readonly VendorOrderService $vendorOrderService,
        private readonly VendorRepository $vendorRepository,
    ) {}

    /**
     * Display list of vendor orders.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $vendor = $this->vendorRepository->findByUserId($user->id);

        if (! $vendor) {
            abort(404, 'Vendor not found');
        }

        // Get filter status from request
        $statusFilter = $request->query('status');

        $orders = $this->vendorOrderService->getVendorOrders($vendor->id, 20);

        // Get order statistics
        $stats = $this->vendorOrderService->getVendorOrderStats($vendor->id);

        return view('pages.vendor.orders.index', compact('orders', 'stats', 'statusFilter'));
    }

    /**
     * Display a specific vendor order.
     */
    public function show(int $id): View
    {
        $user = auth()->user();
        $vendor = $this->vendorRepository->findByUserId($user->id);

        if (! $vendor) {
            abort(404, 'Vendor not found');
        }

        $vendorOrder = $this->vendorOrderService->getVendorOrder($id, $vendor->id);

        return view('pages.vendor.orders.show', compact('vendorOrder'));
    }

    /**
     * Accept a vendor order.
     */
    public function accept(int $id, Request $request): RedirectResponse
    {
        $user = auth()->user();
        $vendor = $this->vendorRepository->findByUserId($user->id);

        if (! $vendor) {
            abort(404, 'Vendor not found');
        }

        try {
            $notes = $request->input('notes');
            $this->vendorOrderService->acceptOrder($id, $vendor->id, $notes);

            return redirect()
                ->route('vendor.orders.show', $id)
                ->with('success', 'Order accepted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Mark vendor order as packed.
     */
    public function pack(int $id, Request $request): RedirectResponse
    {
        $user = auth()->user();
        $vendor = $this->vendorRepository->findByUserId($user->id);

        if (! $vendor) {
            abort(404, 'Vendor not found');
        }

        try {
            $notes = $request->input('notes');
            $this->vendorOrderService->packOrder($id, $vendor->id, $notes);

            return redirect()
                ->route('vendor.orders.show', $id)
                ->with('success', 'Order marked as packed successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Ship vendor order.
     */
    public function ship(int $id, Request $request): RedirectResponse
    {
        $user = auth()->user();
        $vendor = $this->vendorRepository->findByUserId($user->id);

        if (! $vendor) {
            abort(404, 'Vendor not found');
        }

        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'shipping_carrier' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->vendorOrderService->shipOrder(
                $id,
                $vendor->id,
                $request->input('tracking_number'),
                $request->input('shipping_carrier'),
                $request->input('notes')
            );

            return redirect()
                ->route('vendor.orders.show', $id)
                ->with('success', 'Order shipped successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Mark vendor order as delivered.
     */
    public function deliver(int $id): RedirectResponse
    {
        $user = auth()->user();
        $vendor = $this->vendorRepository->findByUserId($user->id);

        if (! $vendor) {
            abort(404, 'Vendor not found');
        }

        try {
            $this->vendorOrderService->deliverOrder($id, $vendor->id);

            return redirect()
                ->route('vendor.orders.show', $id)
                ->with('success', 'Order marked as delivered successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel vendor order.
     */
    public function cancel(int $id, Request $request): RedirectResponse
    {
        $user = auth()->user();
        $vendor = $this->vendorRepository->findByUserId($user->id);

        if (! $vendor) {
            abort(404, 'Vendor not found');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $this->vendorOrderService->cancelOrder(
                $id,
                $vendor->id,
                $request->input('reason')
            );

            return redirect()
                ->route('vendor.orders.index')
                ->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Reject vendor order.
     */
    public function reject(int $id, Request $request): RedirectResponse
    {
        $user = auth()->user();
        $vendor = $this->vendorRepository->findByUserId($user->id);

        if (! $vendor) {
            abort(404, 'Vendor not found');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $this->vendorOrderService->rejectOrder(
                $id,
                $vendor->id,
                $request->input('reason')
            );

            return redirect()
                ->route('vendor.orders.index')
                ->with('success', 'Order rejected successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
