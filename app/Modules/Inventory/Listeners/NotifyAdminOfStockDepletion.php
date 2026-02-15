<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Listeners;

use App\Modules\Inventory\Events\StockDepleted;
use Illuminate\Support\Facades\Log;

class NotifyAdminOfStockDepletion
{
    /**
     * Handle the event.
     */
    public function handle(StockDepleted $event): void
    {
        $inventory = $event->inventory;
        $product = $inventory->product;

        Log::critical('Stock depleted', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity_available' => $inventory->quantity_available,
        ]);

        // Future: Send urgent email to admin
        // Mail::to(config('app.admin_email'))->send(new StockDepletedNotification($inventory));
    }
}
