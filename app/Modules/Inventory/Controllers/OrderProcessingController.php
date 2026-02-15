<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Exceptions\InsufficientStockException;
use App\Modules\Inventory\Services\InventoryService;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Requests\UpdateOrderStatusRequest;
use App\Shared\Enums\OrderStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderProcessingController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Confirm an order and reserve stock.
     */
    public function confirm(Order $order): RedirectResponse
    {
        if ($order->status !== OrderStatus::Pending) {
            return redirect()->back()->with('error', 'Only pending orders can be confirmed.');
        }

        try {
            DB::transaction(function () use ($order) {
                // Reserve stock
                $this->inventoryService->reserveStockForOrder($order);

                // Update order status
                $order->status = OrderStatus::Confirmed;
                $order->save();
            });

            return redirect()->back()->with('success', 'Order confirmed and stock reserved successfully.');
        } catch (InsufficientStockException $e) {
            Log::error('Failed to confirm order due to insufficient stock', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Failed to confirm order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to confirm order. Please try again.');
        }
    }

    /**
     * Mark order as processing.
     */
    public function markAsProcessing(Order $order): RedirectResponse
    {
        if ($order->status !== OrderStatus::Confirmed) {
            return redirect()->back()->with('error', 'Only confirmed orders can be marked as processing.');
        }

        $order->status = OrderStatus::Processing;
        $order->save();

        return redirect()->back()->with('success', 'Order marked as processing.');
    }

    /**
     * Mark order as shipped and finalize inventory.
     */
    public function markAsShipped(Order $order): RedirectResponse
    {
        if (! in_array($order->status, [OrderStatus::Confirmed, OrderStatus::Processing])) {
            return redirect()->back()->with('error', 'Only confirmed or processing orders can be marked as shipped.');
        }

        try {
            DB::transaction(function () use ($order) {
                // Finalize stock (remove from reserved)
                $this->inventoryService->finalizeStockForShippedOrder($order);

                // Update order status
                $order->status = OrderStatus::Shipped;
                $order->save();
            });

            return redirect()->back()->with('success', 'Order marked as shipped and inventory finalized.');
        } catch (\Exception $e) {
            Log::error('Failed to mark order as shipped', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to mark order as shipped. Please try again.');
        }
    }

    /**
     * Mark order as delivered.
     */
    public function markAsDelivered(Order $order): RedirectResponse
    {
        if ($order->status !== OrderStatus::Shipped) {
            return redirect()->back()->with('error', 'Only shipped orders can be marked as delivered.');
        }

        $order->status = OrderStatus::Delivered;
        $order->save();

        return redirect()->back()->with('success', 'Order marked as delivered.');
    }

    /**
     * Cancel an order and restore stock.
     */
    public function cancel(Order $order): RedirectResponse
    {
        if (! $order->status->canBeCancelled()) {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        try {
            DB::transaction(function () use ($order) {
                // Restore stock if order was confirmed (stock was reserved)
                if ($order->status === OrderStatus::Confirmed) {
                    $this->inventoryService->restoreStockForOrder($order);
                }

                // Update order status
                $order->status = OrderStatus::Cancelled;
                $order->save();
            });

            return redirect()->back()->with('success', 'Order cancelled and stock restored successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to cancel order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to cancel order. Please try again.');
        }
    }

    /**
     * Update order status with validation.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $newStatus = OrderStatus::from($request->validated('status'));
        $adminNotes = $request->validated('admin_notes');

        // Handle status transitions with inventory management
        try {
            DB::transaction(function () use ($order, $newStatus, $adminNotes) {
                $oldStatus = $order->status;

                // Handle inventory changes based on status transition
                if ($oldStatus === OrderStatus::Pending && $newStatus === OrderStatus::Confirmed) {
                    $this->inventoryService->reserveStockForOrder($order);
                } elseif ($oldStatus === OrderStatus::Confirmed && $newStatus === OrderStatus::Cancelled) {
                    $this->inventoryService->restoreStockForOrder($order);
                } elseif (in_array($oldStatus, [OrderStatus::Confirmed, OrderStatus::Processing]) && $newStatus === OrderStatus::Shipped) {
                    $this->inventoryService->finalizeStockForShippedOrder($order);
                }

                // Update order
                $order->status = $newStatus;
                if ($adminNotes) {
                    $order->admin_notes = $adminNotes;
                }
                $order->save();
            });

            return redirect()->back()->with('success', 'Order status updated successfully.');
        } catch (InsufficientStockException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Failed to update order status', [
                'order_id' => $order->id,
                'new_status' => $newStatus->value,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to update order status. Please try again.');
        }
    }
}
