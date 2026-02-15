<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Listeners;

use App\Modules\Inventory\Events\LowStockDetected;
use Illuminate\Support\Facades\Log;

class NotifyAdminOfLowStock
{
    /**
     * Handle the event.
     */
    public function handle(LowStockDetected $event): void
    {
        $inventory = $event->inventory;
        $product = $inventory->product;

        Log::warning('Low stock detected', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity_available' => $inventory->quantity_available,
            'threshold' => $inventory->low_stock_threshold,
        ]);

        // Future: Send email to admin
        // Mail::to(config('app.admin_email'))->send(new LowStockNotification($inventory));
    }
}
