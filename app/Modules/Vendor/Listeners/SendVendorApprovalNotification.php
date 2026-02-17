<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Listeners;

use App\Modules\Vendor\Events\VendorApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendVendorApprovalNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(VendorApproved $event): void
    {
        // Log vendor approval
        Log::info('Vendor approved', [
            'vendor_id' => $event->vendor->id,
            'business_name' => $event->vendor->business_name,
            'approved_by' => $event->vendor->approved_by,
        ]);

        // TODO: Send email notification to vendor about approval
        // TODO: Send welcome email with vendor dashboard link
    }
}
