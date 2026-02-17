<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Listeners;

use App\Modules\Vendor\Events\VendorRejected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendVendorRejectionNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(VendorRejected $event): void
    {
        // Log vendor rejection
        Log::info('Vendor rejected', [
            'vendor_id' => $event->vendor->id,
            'business_name' => $event->vendor->business_name,
            'rejection_reason' => $event->vendor->rejection_reason,
        ]);

        // TODO: Send email notification to vendor about rejection
        // TODO: Include rejection reason in notification
    }
}
