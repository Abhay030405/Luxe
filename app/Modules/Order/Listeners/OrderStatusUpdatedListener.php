<?php

declare(strict_types=1);

namespace App\Modules\Order\Listeners;

use App\Modules\Order\Events\OrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * OrderStatusUpdatedListener handles actions after order status changes.
 * Can be extended to send notifications based on status.
 */
class OrderStatusUpdatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusUpdated $event): void
    {
        $order = $event->order;

        Log::info('Order status updated', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'previous_status' => $event->previousStatus->value,
            'new_status' => $event->newStatus->value,
        ]);

        // TODO: Send status update notification to customer
        // match ($event->newStatus) {
        //     OrderStatus::Confirmed => // Send confirmation email
        //     OrderStatus::Shipped => // Send shipping notification with tracking
        //     OrderStatus::Delivered => // Send delivery confirmation
        //     default => null,
        // };
    }
}
