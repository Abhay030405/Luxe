<?php

declare(strict_types=1);

namespace App\Modules\Order\Services;

use App\Events\VendorOrderAccepted;
use App\Events\VendorOrderCancelled;
use App\Events\VendorOrderDelivered;
use App\Events\VendorOrderRejected;
use App\Events\VendorOrderShipped;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Order\Models\VendorOrder;
use App\Modules\Order\Repositories\VendorOrderRepository;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Repositories\Contracts\ProductRepositoryInterface;
use App\Modules\Vendor\Repositories\VendorRepository;
use App\Shared\Enums\VendorOrderStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * VendorOrderService handles vendor order operations and order splitting.
 * This is the CRITICAL service that transforms customer orders into vendor orders.
 */
class VendorOrderService
{
    public function __construct(
        private readonly VendorOrderRepository $vendorOrderRepository,
        private readonly VendorRepository $vendorRepository,
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    /**
     * Split a customer order into vendor orders.
     * This is called automatically after a customer places an order.
     *
     * Groups items by vendor and creates separate vendor orders.
     */
    public function splitOrderIntoVendorOrders(Order $order): Collection
    {
        return DB::transaction(function () use ($order) {
            // Step 1: Get all items and group by vendor_id
            $itemsByVendor = $this->groupItemsByVendor($order->items);

            // Step 2: Create vendor order for each vendor
            $vendorOrders = new Collection;

            foreach ($itemsByVendor as $vendorId => $items) {
                $vendorOrder = $this->createVendorOrderForItems($order, $vendorId, $items);
                $vendorOrders->push($vendorOrder);
            }

            Log::info('Order split into vendor orders', [
                'order_id' => $order->id,
                'vendor_order_count' => $vendorOrders->count(),
                'vendor_ids' => $itemsByVendor->keys()->toArray(),
            ]);

            return $vendorOrders;
        });
    }

    /**
     * Group order items by their product's vendor_id.
     */
    private function groupItemsByVendor(Collection $orderItems): Collection
    {
        return $orderItems->groupBy(function (OrderItem $item) {
            // Get vendor_id from the product
            $product = Product::find($item->product_id);

            if (! $product || ! $product->vendor_id) {
                throw new InvalidArgumentException(
                    "Product {$item->product_id} has no vendor assigned. Cannot split order."
                );
            }

            return $product->vendor_id;
        });
    }

    /**
     * Create a vendor order for a specific vendor and their items.
     */
    private function createVendorOrderForItems(Order $order, int $vendorId, Collection $items): VendorOrder
    {
        // Step 1: Get vendor to fetch commission rate
        $vendor = $this->vendorRepository->findById($vendorId);

        if (! $vendor) {
            throw new InvalidArgumentException("Vendor {$vendorId} not found");
        }

        // Step 2: Calculate subtotal for this vendor
        $subtotal = $items->sum('subtotal');

        // Step 3: Calculate commission
        $commissionRate = $vendor->commission_rate ?? 0.00;
        $commissionAmount = ($subtotal * $commissionRate) / 100;
        $vendorEarnings = $subtotal - $commissionAmount;

        // Step 4: Generate unique vendor order number
        $vendorOrderNumber = $this->generateVendorOrderNumber($vendor->slug);

        // Step 5: Create vendor order
        $vendorOrder = VendorOrder::create([
            'order_id' => $order->id,
            'vendor_id' => $vendorId,
            'vendor_order_number' => $vendorOrderNumber,
            'status' => VendorOrderStatus::Pending,
            'subtotal' => $subtotal,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'vendor_earnings' => $vendorEarnings,
        ]);

        // Step 6: Link order items to this vendor order
        OrderItem::whereIn('id', $items->pluck('id'))->update([
            'vendor_order_id' => $vendorOrder->id,
        ]);

        Log::info('Vendor order created', [
            'vendor_order_id' => $vendorOrder->id,
            'vendor_order_number' => $vendorOrderNumber,
            'vendor_id' => $vendorId,
            'items_count' => $items->count(),
            'subtotal' => $subtotal,
            'vendor_earnings' => $vendorEarnings,
        ]);

        return $vendorOrder;
    }

    /**
     * Generate unique vendor order number.
     * Format: VND-{VENDOR_SLUG}-{YEAR}{MONTH}{DAY}-{SEQUENCE}
     * Example: VND-TECHSTORE-20260217-00001
     */
    private function generateVendorOrderNumber(string $vendorSlug): string
    {
        $prefix = 'VND-'.strtoupper($vendorSlug);
        $datePart = now()->format('Ymd');

        // Get last vendor order number for today
        $lastOrder = VendorOrder::where('vendor_order_number', 'like', "{$prefix}-{$datePart}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            // Extract sequence and increment
            $lastSequence = (int) substr($lastOrder->vendor_order_number, -5);
            $sequence = $lastSequence + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('%s-%s-%05d', $prefix, $datePart, $sequence);
    }

    /**
     * Get vendor orders for a specific vendor.
     */
    public function getVendorOrders(int $vendorId, int $perPage = 15)
    {
        return $this->vendorOrderRepository->getVendorOrders($vendorId, $perPage);
    }

    /**
     * Get vendor order by ID with authorization check.
     */
    public function getVendorOrder(int $vendorOrderId, int $vendorId): VendorOrder
    {
        $vendorOrder = $this->vendorOrderRepository->findByIdOrFail($vendorOrderId);

        if ($vendorOrder->vendor_id !== $vendorId) {
            throw new InvalidArgumentException('Vendor order not found or access denied.');
        }

        return $vendorOrder;
    }

    /**
     * Accept a vendor order.
     */
    public function acceptOrder(int $vendorOrderId, int $vendorId, ?string $notes = null): bool
    {
        $vendorOrder = $this->getVendorOrder($vendorOrderId, $vendorId);

        if (! $vendorOrder->canBeAccepted()) {
            throw new InvalidArgumentException('Order cannot be accepted in current status.');
        }

        $result = $this->vendorOrderRepository->acceptOrder($vendorOrderId, $notes);

        if ($result) {
            // Reload the vendor order to get fresh data
            $vendorOrder->refresh();

            // Dispatch event for email notification
            event(new VendorOrderAccepted($vendorOrder));

            Log::info('Vendor order accepted', [
                'vendor_order_id' => $vendorOrderId,
                'vendor_id' => $vendorId,
            ]);
        }

        return $result;
    }

    /**
     * Mark vendor order as packed.
     */
    public function packOrder(int $vendorOrderId, int $vendorId, ?string $notes = null): bool
    {
        $vendorOrder = $this->getVendorOrder($vendorOrderId, $vendorId);

        if (! $vendorOrder->canBePacked()) {
            throw new InvalidArgumentException('Order cannot be marked as packed in current status.');
        }

        $result = $this->vendorOrderRepository->packOrder($vendorOrderId, $notes);

        if ($result) {
            Log::info('Vendor order packed', [
                'vendor_order_id' => $vendorOrderId,
                'vendor_id' => $vendorId,
            ]);

            // TODO: Dispatch VendorOrderPacked event
        }

        return $result;
    }

    /**
     * Ship vendor order.
     */
    public function shipOrder(int $vendorOrderId, int $vendorId, string $trackingNumber, string $carrier, ?string $notes = null): bool
    {
        $vendorOrder = $this->getVendorOrder($vendorOrderId, $vendorId);

        if (! $vendorOrder->canBeShipped()) {
            throw new InvalidArgumentException('Order cannot be shipped in current status.');
        }

        $result = $this->vendorOrderRepository->shipOrder($vendorOrderId, $trackingNumber, $carrier, $notes);

        if ($result) {
            Log::info('Vendor order shipped', [
                'vendor_order_id' => $vendorOrderId,
                'vendor_id' => $vendorId,
                'tracking_number' => $trackingNumber,
                'carrier' => $carrier,
            ]);

            // TODO: Dispatch VendorOrderShipped event
            // TODO: Send tracking info to customer
        }

        return $result;
    }

    /**
     * Mark vendor order as delivered.
     */
    public function deliverOrder(int $vendorOrderId, int $vendorId): bool
    {
        $vendorOrder = $this->getVendorOrder($vendorOrderId, $vendorId);

        if (! $vendorOrder->canBeDelivered()) {
            throw new InvalidArgumentException('Order cannot be marked as delivered in current status.');
        }

        $result = $this->vendorOrderRepository->deliverOrder($vendorOrderId);

        if ($result) {
            // Reload the vendor order to get fresh data
            $vendorOrder->refresh();

            // Dispatch event for email notification
            event(new VendorOrderDelivered($vendorOrder));

            Log::info('Vendor order delivered', [
                'vendor_order_id' => $vendorOrderId,
                'vendor_id' => $vendorId,
            ]);

            // TODO: Release vendor earnings for payout
        }

        return $result;
    }

    /**
     * Cancel vendor order.
     */
    public function cancelOrder(int $vendorOrderId, int $vendorId, string $reason): bool
    {
        $vendorOrder = $this->getVendorOrder($vendorOrderId, $vendorId);

        if (! $vendorOrder->canBeCancelled()) {
            throw new InvalidArgumentException('Order cannot be cancelled in current status.');
        }

        $result = $this->vendorOrderRepository->cancelOrder($vendorOrderId, $reason);

        if ($result) {
            // Restore product stock for cancelled order
            $this->restoreProductStock($vendorOrder);

            // Reload the vendor order to get fresh data
            $vendorOrder->refresh();

            // Dispatch event for email notification
            event(new VendorOrderCancelled($vendorOrder));

            Log::info('Vendor order cancelled', [
                'vendor_order_id' => $vendorOrderId,
                'vendor_id' => $vendorId,
                'reason' => $reason,
            ]);
        }

        return $result;
    }

    /**
     * Reject vendor order (only for pending orders).
     */
    public function rejectOrder(int $vendorOrderId, int $vendorId, string $reason): bool
    {
        $vendorOrder = $this->getVendorOrder($vendorOrderId, $vendorId);

        if ($vendorOrder->status !== VendorOrderStatus::Pending) {
            throw new InvalidArgumentException('Only pending orders can be rejected.');
        }

        $result = $this->vendorOrderRepository->rejectOrder($vendorOrderId, $reason);

        if ($result) {
            // Restore product stock for rejected order
            $this->restoreProductStock($vendorOrder);

            // Reload the vendor order to get fresh data
            $vendorOrder->refresh();

            // Dispatch event for email notification
            event(new VendorOrderRejected($vendorOrder));

            Log::info('Vendor order rejected', [
                'vendor_order_id' => $vendorOrderId,
                'vendor_id' => $vendorId,
                'reason' => $reason,
            ]);
        }

        return $result;
    }

    /**
     * Get vendor order statistics.
     */
    public function getVendorOrderStats(int $vendorId): array
    {
        return $this->vendorOrderRepository->getVendorOrderStats($vendorId);
    }

    /**
     * Get pending orders for vendor.
     */
    public function getPendingOrders(int $vendorId): Collection
    {
        return $this->vendorOrderRepository->getPendingVendorOrders($vendorId);
    }

    /**
     * Get active orders for vendor.
     */
    public function getActiveOrders(int $vendorId): Collection
    {
        return $this->vendorOrderRepository->getActiveVendorOrders($vendorId);
    }

    /**
     * Restore product stock when vendor cancels or rejects an order.
     * Increments stock_quantity for all products in the vendor order.
     */
    private function restoreProductStock(VendorOrder $vendorOrder): void
    {
        DB::transaction(function () use ($vendorOrder) {
            foreach ($vendorOrder->items as $item) {
                $product = $this->productRepository->findById($item->product_id);

                if (! $product) {
                    Log::warning('Cannot restore stock - product not found', [
                        'product_id' => $item->product_id,
                        'vendor_order_id' => $vendorOrder->id,
                    ]);

                    continue;
                }

                // Restore stock by adding back the quantity
                $newStock = $product->stock_quantity + $item->quantity;
                $product->stock_quantity = $newStock;
                $product->save();

                Log::info('Product stock restored', [
                    'product_id' => $product->id,
                    'quantity_restored' => $item->quantity,
                    'new_stock' => $newStock,
                    'vendor_order_id' => $vendorOrder->id,
                ]);
            }
        });
    }
}
