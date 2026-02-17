<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Listeners;

use App\Modules\Vendor\Events\VendorRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendVendorRegistrationNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(VendorRegistered $event): void
    {
        // Log vendor registration
        Log::info('New vendor registered', [
            'vendor_id' => $event->vendor->id,
            'business_name' => $event->vendor->business_name,
            'user_id' => $event->vendor->user_id,
        ]);

        // TODO: Send email notification to vendor
        // TODO: Send notification to admins for approval
    }
}
