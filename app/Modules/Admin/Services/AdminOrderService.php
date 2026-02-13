<?php

declare(strict_types=1);

namespace App\Modules\Admin\Services;

use App\Modules\Order\Models\Order;
use App\Shared\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AdminOrderService
{
    /**
     * Update the status of an order.
     */
    public function updateOrderStatus(int $orderId, OrderStatus $status, ?string $adminNotes = null): Order
    {
        return DB::transaction(function () use ($orderId, $status, $adminNotes) {
            $order = Order::findOrFail($orderId);

            // Validate status transition
            $this->validateStatusTransition($order->status, $status);

            $order->status = $status;
            if ($adminNotes) {
                $order->admin_notes = $adminNotes;
            }
            $order->save();

            return $order;
        });
    }

    /**
     * Cancel an order.
     */
    public function cancelOrder(int $orderId, ?string $reason = null): Order
    {
        return DB::transaction(function () use ($orderId, $reason) {
            $order = Order::findOrFail($orderId);

            if (! $order->canBeCancelled()) {
                throw new InvalidArgumentException('Order cannot be cancelled at this stage.');
            }

            $order->status = OrderStatus::Cancelled;
            if ($reason) {
                $order->admin_notes = ($order->admin_notes ? $order->admin_notes."\n\n" : '').
                    'Cancellation Reason: '.$reason;
            }
            $order->save();

            return $order;
        });
    }

    /**
     * Validate if status transition is allowed.
     */
    private function validateStatusTransition(OrderStatus $currentStatus, OrderStatus $newStatus): void
    {
        // Cannot change status of cancelled or refunded orders
        if (in_array($currentStatus, [OrderStatus::Cancelled, OrderStatus::Refunded])) {
            throw new InvalidArgumentException('Cannot change status of cancelled or refunded orders.');
        }

        // Cannot change delivered orders
        if ($currentStatus === OrderStatus::Delivered && $newStatus !== OrderStatus::Refunded) {
            throw new InvalidArgumentException('Delivered orders can only be refunded.');
        }
    }
}
