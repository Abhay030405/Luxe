<?php

declare(strict_types=1);

namespace App\Events;

use App\Modules\Order\Models\VendorOrder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VendorOrderDelivered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public VendorOrder $vendorOrder
    ) {}
}
