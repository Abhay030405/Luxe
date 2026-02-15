<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Listeners;

use App\Modules\Inventory\Events\OrderConfirmed;
use App\Modules\Inventory\Services\InventoryService;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReserveStockForConfirmedOrder implements ShouldQueue
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
    public function handle(OrderConfirmed $event): void
    {
        $this->inventoryService->reserveStockForOrder($event->order);
    }
}
