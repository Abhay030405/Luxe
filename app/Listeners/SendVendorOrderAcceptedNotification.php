<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\VendorOrderAccepted;
use App\Mail\VendorOrderAcceptedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVendorOrderAcceptedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(VendorOrderAccepted $event): void
    {
        Mail::to($event->vendorOrder->order->customer->email)
            ->send(new VendorOrderAcceptedMail($event->vendorOrder));
    }
}
