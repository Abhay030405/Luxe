<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\VendorOrderRejected;
use App\Mail\VendorOrderRejectedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVendorOrderRejectedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(VendorOrderRejected $event): void
    {
        Mail::to($event->vendorOrder->order->customer->email)
            ->send(new VendorOrderRejectedMail($event->vendorOrder));
    }
}
