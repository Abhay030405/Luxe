<?php

declare(strict_types=1);

namespace App\Modules\Order\Repositories;

use App\Modules\Order\Models\VendorOrder;
use App\Modules\Order\Repositories\Contracts\VendorOrderRepositoryInterface;
use App\Shared\Enums\VendorOrderStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Repository for VendorOrder data access.
 * Handles all database operations for vendor orders.
 */
class VendorOrderRepository implements VendorOrderRepositoryInterface
{
    /**
     * Find vendor order by ID.
     */
    public function findById(int $id): ?VendorOrder
    {
        return VendorOrder::find($id);
    }

    /**
     * Find vendor order by ID or fail.
     */
    public function findByIdOrFail(int $id): VendorOrder
    {
        return VendorOrder::with(['order', 'vendor', 'items.product'])->findOrFail($id);
    }

    /**
     * Find vendor order by vendor order number.
     */
    public function findByVendorOrderNumber(string $vendorOrderNumber): ?VendorOrder
    {
        return VendorOrder::where('vendor_order_number', $vendorOrderNumber)->first();
    }

    /**
     * Get vendor orders with pagination.
     */
    public function getVendorOrders(int $vendorId, int $perPage = 15): LengthAwarePaginator
    {
        return VendorOrder::forVendor($vendorId)
            ->with(['order.user', 'items.product'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get vendor orders by status.
     */
    public function getVendorOrdersByStatus(int $vendorId, VendorOrderStatus $status): Collection
    {
        return VendorOrder::forVendor($vendorId)
            ->withStatus($status)
            ->with(['order.user', 'items.product'])
            ->latest('created_at')
            ->get();
    }

    /**
     * Get pending vendor orders.
     */
    public function getPendingVendorOrders(int $vendorId): Collection
    {
        return VendorOrder::forVendor($vendorId)
            ->pending()
            ->with(['order.user', 'items.product'])
            ->latest('created_at')
            ->get();
    }

    /**
     * Get active vendor orders (non-final statuses).
     */
    public function getActiveVendorOrders(int $vendorId): Collection
    {
        return VendorOrder::forVendor($vendorId)
            ->active()
            ->with(['order.user', 'items.product'])
            ->latest('created_at')
            ->get();
    }

    /**
     * Check if vendor owns the order.
     */
    public function vendorOwnsOrder(int $vendorId, int $vendorOrderId): bool
    {
        return VendorOrder::where('id', $vendorOrderId)
            ->where('vendor_id', $vendorId)
            ->exists();
    }

    /**
     * Update vendor order status.
     */
    public function updateStatus(int $vendorOrderId, VendorOrderStatus $status, ?array $additionalData = null): bool
    {
        $data = ['status' => $status];

        if ($additionalData) {
            $data = array_merge($data, $additionalData);
        }

        return VendorOrder::where('id', $vendorOrderId)->update($data) > 0;
    }

    /**
     * Accept vendor order.
     */
    public function acceptOrder(int $vendorOrderId, ?string $notes = null): bool
    {
        $vendorOrder = $this->findByIdOrFail($vendorOrderId);

        if (! $vendorOrder->canBeAccepted()) {
            return false;
        }

        $data = [
            'status' => VendorOrderStatus::Accepted,
            'accepted_at' => now(),
        ];

        if ($notes) {
            $data['vendor_notes'] = $notes;
        }

        return $this->updateStatus($vendorOrderId, VendorOrderStatus::Accepted, $data);
    }

    /**
     * Mark vendor order as packed.
     */
    public function packOrder(int $vendorOrderId, ?string $notes = null): bool
    {
        $vendorOrder = $this->findByIdOrFail($vendorOrderId);

        if (! $vendorOrder->canBePacked()) {
            return false;
        }

        $data = [
            'status' => VendorOrderStatus::Packed,
            'packed_at' => now(),
        ];

        if ($notes) {
            $data['vendor_notes'] = $notes;
        }

        return $this->updateStatus($vendorOrderId, VendorOrderStatus::Packed, $data);
    }

    /**
     * Mark vendor order as shipped.
     */
    public function shipOrder(int $vendorOrderId, string $trackingNumber, string $carrier, ?string $notes = null): bool
    {
        $vendorOrder = $this->findByIdOrFail($vendorOrderId);

        if (! $vendorOrder->canBeShipped()) {
            return false;
        }

        $data = [
            'status' => VendorOrderStatus::Shipped,
            'shipped_at' => now(),
            'tracking_number' => $trackingNumber,
            'shipping_carrier' => $carrier,
        ];

        if ($notes) {
            $data['vendor_notes'] = $notes;
        }

        return $this->updateStatus($vendorOrderId, VendorOrderStatus::Shipped, $data);
    }

    /**
     * Mark vendor order as delivered.
     */
    public function deliverOrder(int $vendorOrderId): bool
    {
        $vendorOrder = $this->findByIdOrFail($vendorOrderId);

        if (! $vendorOrder->canBeDelivered()) {
            return false;
        }

        $data = [
            'status' => VendorOrderStatus::Delivered,
            'delivered_at' => now(),
        ];

        return $this->updateStatus($vendorOrderId, VendorOrderStatus::Delivered, $data);
    }

    /**
     * Cancel vendor order.
     */
    public function cancelOrder(int $vendorOrderId, string $reason): bool
    {
        $vendorOrder = $this->findByIdOrFail($vendorOrderId);

        if (! $vendorOrder->canBeCancelled()) {
            return false;
        }

        $data = [
            'status' => VendorOrderStatus::Cancelled,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ];

        return $this->updateStatus($vendorOrderId, VendorOrderStatus::Cancelled, $data);
    }

    /**
     * Reject vendor order.
     */
    public function rejectOrder(int $vendorOrderId, string $reason): bool
    {
        $vendorOrder = $this->findByIdOrFail($vendorOrderId);

        if (! $vendorOrder->status === VendorOrderStatus::Pending) {
            return false;
        }

        $data = [
            'status' => VendorOrderStatus::Rejected,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ];

        return $this->updateStatus($vendorOrderId, VendorOrderStatus::Rejected, $data);
    }

    /**
     * Get all vendor orders for a customer order.
     */
    public function getVendorOrdersForCustomerOrder(int $orderId): Collection
    {
        return VendorOrder::where('order_id', $orderId)
            ->with(['vendor', 'items.product'])
            ->get();
    }

    /**
     * Get vendor order statistics.
     */
    public function getVendorOrderStats(int $vendorId): array
    {
        $stats = VendorOrder::forVendor($vendorId)
            ->select([
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_orders'),
                DB::raw('SUM(CASE WHEN status = "accepted" THEN 1 ELSE 0 END) as accepted_orders'),
                DB::raw('SUM(CASE WHEN status = "packed" THEN 1 ELSE 0 END) as packed_orders'),
                DB::raw('SUM(CASE WHEN status = "shipped" THEN 1 ELSE 0 END) as shipped_orders'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered_orders'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_orders'),
                DB::raw('SUM(vendor_earnings) as total_earnings'),
                DB::raw('SUM(commission_amount) as total_commission_paid'),
            ])
            ->first();

        return [
            'total_orders' => (int) $stats->total_orders,
            'pending_orders' => (int) $stats->pending_orders,
            'accepted_orders' => (int) $stats->accepted_orders,
            'packed_orders' => (int) $stats->packed_orders,
            'shipped_orders' => (int) $stats->shipped_orders,
            'delivered_orders' => (int) $stats->delivered_orders,
            'cancelled_orders' => (int) $stats->cancelled_orders,
            'total_earnings' => (float) $stats->total_earnings,
            'total_commission_paid' => (float) $stats->total_commission_paid,
        ];
    }
}
