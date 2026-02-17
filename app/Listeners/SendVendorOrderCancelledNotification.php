<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\VendorOrderCancelled;
use App\Mail\VendorOrderCancelledMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVendorOrderCancelledNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(VendorOrderCancelled $event): void
    {
        Mail::to($event->vendorOrder->order->customer->email)
            ->send(new VendorOrderCancelledMail($event->vendorOrder));
    }
}
