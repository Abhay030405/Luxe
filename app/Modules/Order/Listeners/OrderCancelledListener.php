<?php

declare(strict_types=1);

namespace App\Modules\Order\Listeners;

use App\Modules\Order\Events\OrderCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * OrderCancelledListener handles actions after order cancellation.
 * Typically restores inventory and notifies relevant parties.
 */
class OrderCancelledListener implements ShouldQueue
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
    public function handle(OrderCancelled $event): void
    {
        $order = $event->order;

        Log::info('Order cancelled event handled', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'reason' => $event->reason,
        ]);

        // TODO: Restore product stock
        // foreach ($order->items as $item) {
        //     $item->product->increment('stock_quantity', $item->quantity);
        // }

        // TODO: Send cancellation notification to customer
        // Mail::to($order->user->email)->send(new OrderCancelledMail($order, $event->reason));

        // TODO: Notify admins
        // Notification::send(User::admins()->get(), new OrderCancelledNotification($order));
    }
}
