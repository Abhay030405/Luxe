<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Listeners;

use App\Modules\Vendor\Events\VendorSuspended;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendVendorSuspensionNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(VendorSuspended $event): void
    {
        // Log vendor suspension
        Log::info('Vendor suspended', [
            'vendor_id' => $event->vendor->id,
            'business_name' => $event->vendor->business_name,
        ]);

        // TODO: Send email notification to vendor about suspension
        // TODO: Notify vendor about reason and how to resolve
    }
}
