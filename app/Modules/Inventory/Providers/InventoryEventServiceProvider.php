<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Providers;

use App\Modules\Inventory\Events\LowStockDetected;
use App\Modules\Inventory\Events\OrderCancelled;
use App\Modules\Inventory\Events\OrderConfirmed;
use App\Modules\Inventory\Events\OrderShipped;
use App\Modules\Inventory\Events\StockDepleted;
use App\Modules\Inventory\Listeners\FinalizeStockForShippedOrder;
use App\Modules\Inventory\Listeners\NotifyAdminOfLowStock;
use App\Modules\Inventory\Listeners\NotifyAdminOfStockDepletion;
use App\Modules\Inventory\Listeners\ReserveStockForConfirmedOrder;
use App\Modules\Inventory\Listeners\RestoreStockForCancelledOrder;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class InventoryEventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        OrderConfirmed::class => [
            ReserveStockForConfirmedOrder::class,
        ],
        OrderCancelled::class => [
            RestoreStockForCancelledOrder::class,
        ],
        OrderShipped::class => [
            FinalizeStockForShippedOrder::class,
        ],
        LowStockDetected::class => [
            NotifyAdminOfLowStock::class,
        ],
        StockDepleted::class => [
            NotifyAdminOfStockDepletion::class,
        ],
    ];
}
