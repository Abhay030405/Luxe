<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Events;

use App\Modules\Vendor\Models\VendorApplication;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VendorApplicationSubmitted
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public VendorApplication $application
    ) {}
}
