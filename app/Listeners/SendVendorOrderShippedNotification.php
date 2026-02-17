<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\VendorOrderShipped;
use App\Mail\VendorOrderShippedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVendorOrderShippedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(VendorOrderShipped $event): void
    {
        Mail::to($event->vendorOrder->order->customer->email)
            ->send(new VendorOrderShippedMail($event->vendorOrder));
    }
}
