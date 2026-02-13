<?php

declare(strict_types=1);

namespace App\Modules\Order\Listeners;

use App\Modules\Order\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * OrderPlacedListener handles actions after order placement.
 * Currently logs the order, but can be extended to:
 * - Send order confirmation email
 * - Send SMS notification
 * - Notify admins
 * - Update analytics
 * - Trigger inventory management
 */
class OrderPlacedListener implements ShouldQueue
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
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;

        Log::info('Order placed event handled', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'user_id' => $order->user_id,
            'total_amount' => $order->total_amount,
            'items_count' => $order->items->count(),
        ]);

        // TODO: Send order confirmation email
        // Mail::to($order->user->email)->send(new OrderConfirmationMail($order));

        // TODO: Send notification to user
        // $order->user->notify(new OrderPlacedNotification($order));

        // TODO: Notify admins about new order
        // Notification::send(User::admins()->get(), new NewOrderNotification($order));
    }
}
