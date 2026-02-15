<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Listeners;

use App\Modules\Inventory\Events\OrderShipped;
use App\Modules\Inventory\Services\InventoryService;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinalizeStockForShippedOrder implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(OrderShipped $event): void
    {
        $this->inventoryService->finalizeStockForShippedOrder($event->order);
    }
}
