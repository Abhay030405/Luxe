<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\VendorOrderDelivered;
use App\Mail\VendorOrderDeliveredMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVendorOrderDeliveredNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(VendorOrderDelivered $event): void
    {
        Mail::to($event->vendorOrder->order->customer->email)
            ->send(new VendorOrderDeliveredMail($event->vendorOrder));
    }
}
