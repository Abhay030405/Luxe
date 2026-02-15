<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Inventory\Models\Inventory;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products
        $products = Product::all();

        foreach ($products as $product) {
            // Create inventory if it doesn't exist
            Inventory::firstOrCreate(
                ['product_id' => $product->id],
                [
                    'quantity_available' => $product->stock_quantity ?? 0,
                    'quantity_reserved' => 0,
                    'low_stock_threshold' => 10,
                ]
            );
        }

        $this->command->info('Inventory records created for all products.');
    }
}
