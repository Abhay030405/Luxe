<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Observers;

use App\Modules\Inventory\Models\Inventory;
use App\Modules\Product\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     * Automatically create inventory record when product is created.
     */
    public function created(Product $product): void
    {
        Inventory::create([
            'product_id' => $product->id,
            'quantity_available' => $product->stock_quantity ?? 0,
            'quantity_reserved' => 0,
            'low_stock_threshold' => 10,
        ]);
    }
}
