<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Modules\Order\Repositories\Contracts\VendorOrderRepositoryInterface;
use App\Modules\Vendor\Repositories\Contracts\VendorRepositoryInterface;
use App\Shared\Enums\VendorOrderStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Admin Vendor Order Controller.
 * Allows admins to monitor and manage all vendor orders across the marketplace.
 */
class VendorOrderController
{
    public function __construct(
        private readonly VendorOrderRepositoryInterface $vendorOrderRepository,
        private readonly VendorRepositoryInterface $vendorRepository,
    ) {}

    /**
     * Display vendor orders dashboard with filters and statistics.
     */
    public function index(Request $request): View
    {
        $query = $this->vendorOrderRepository->query();

        // Apply filters
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', VendorOrderStatus::from($request->status));
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('vendor_order_number', 'like', '%'.$request->search.'%')
                    ->orWhereHas('order', fn ($orderQuery) => $orderQuery->where('order_number', 'like', '%'.$request->search.'%')
                    );
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get paginated orders with relationships
        $vendorOrders = $query->with(['vendor', 'order.customer', 'items.product'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Get statistics for all vendor orders
        $statistics = $this->getVendorOrderStatistics();

        // Get all active vendors for filter dropdown
        $vendors = $this->vendorRepository->getActiveVendors();

        return view('pages.admin.vendor-orders.index', compact(
            'vendorOrders',
            'statistics',
            'vendors'
        ));
    }

    /**
     * Show detailed view of a specific vendor order.
     */
    public function show(int $id): View
    {
        $vendorOrder = $this->vendorOrderRepository->findById($id);

        if (! $vendorOrder) {
            abort(404, 'Vendor order not found');
        }

        // Load all necessary relationships
        $vendorOrder->load([
            'vendor',
            'order.customer.profile',
            'order.shippingAddress',
            'items.product.images',
        ]);

        return view('pages.admin.vendor-orders.show', compact('vendorOrder'));
    }

    /**
     * Get marketplace-wide vendor order statistics.
     */
    private function getVendorOrderStatistics(): array
    {
        $baseQuery = $this->vendorOrderRepository->query();

        return [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', VendorOrderStatus::Pending)->count(),
            'accepted' => (clone $baseQuery)->where('status', VendorOrderStatus::Accepted)->count(),
            'packed' => (clone $baseQuery)->where('status', VendorOrderStatus::Packed)->count(),
            'shipped' => (clone $baseQuery)->where('status', VendorOrderStatus::Shipped)->count(),
            'delivered' => (clone $baseQuery)->where('status', VendorOrderStatus::Delivered)->count(),
            'cancelled' => (clone $baseQuery)->where('status', VendorOrderStatus::Cancelled)->count(),
            'rejected' => (clone $baseQuery)->where('status', VendorOrderStatus::Rejected)->count(),
            'total_earnings' => (clone $baseQuery)
                ->whereIn('status', [VendorOrderStatus::Delivered])
                ->sum('vendor_earnings'),
            'total_commission' => (clone $baseQuery)
                ->whereIn('status', [VendorOrderStatus::Delivered])
                ->sum('commission_amount'),
        ];
    }
}
