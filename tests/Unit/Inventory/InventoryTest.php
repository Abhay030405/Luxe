<?php

declare(strict_types=1);

namespace Tests\Unit\Inventory;

use App\Modules\Inventory\Models\Inventory;
use App\Modules\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_belongs_to_product(): void
    {
        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $inventory->product);
        $this->assertEquals($product->id, $inventory->product->id);
    }

    public function test_total_stock_attribute(): void
    {
        $inventory = Inventory::factory()->create([
            'quantity_available' => 50,
            'quantity_reserved' => 10,
        ]);

        $this->assertEquals(60, $inventory->total_stock);
    }

    public function test_is_low_stock_returns_true_when_below_threshold(): void
    {
        $inventory = Inventory::factory()->create([
            'quantity_available' => 5,
            'low_stock_threshold' => 10,
        ]);

        $this->assertTrue($inventory->isLowStock());
    }

    public function test_is_low_stock_returns_false_when_above_threshold(): void
    {
        $inventory = Inventory::factory()->create([
            'quantity_available' => 20,
            'low_stock_threshold' => 10,
        ]);

        $this->assertFalse($inventory->isLowStock());
    }

    public function test_is_out_of_stock_returns_true_when_zero(): void
    {
        $inventory = Inventory::factory()->create(['quantity_available' => 0]);

        $this->assertTrue($inventory->isOutOfStock());
    }

    public function test_is_out_of_stock_returns_false_when_has_stock(): void
    {
        $inventory = Inventory::factory()->create(['quantity_available' => 10]);

        $this->assertFalse($inventory->isOutOfStock());
    }

    public function test_has_available_stock_returns_true_when_sufficient(): void
    {
        $inventory = Inventory::factory()->create(['quantity_available' => 50]);

        $this->assertTrue($inventory->hasAvailableStock(20));
    }

    public function test_has_available_stock_returns_false_when_insufficient(): void
    {
        $inventory = Inventory::factory()->create(['quantity_available' => 5]);

        $this->assertFalse($inventory->hasAvailableStock(10));
    }

    public function test_low_stock_scope(): void
    {
        Inventory::factory()->create([
            'quantity_available' => 5,
            'low_stock_threshold' => 10,
        ]);

        Inventory::factory()->create([
            'quantity_available' => 20,
            'low_stock_threshold' => 10,
        ]);

        $lowStockItems = Inventory::lowStock()->get();

        $this->assertCount(1, $lowStockItems);
    }

    public function test_out_of_stock_scope(): void
    {
        Inventory::factory()->outOfStock()->create();
        Inventory::factory()->create(['quantity_available' => 20]);

        $outOfStockItems = Inventory::outOfStock()->get();

        $this->assertCount(1, $outOfStockItems);
    }

    public function test_in_stock_scope(): void
    {
        Inventory::factory()->outOfStock()->create();
        Inventory::factory()->create(['quantity_available' => 20]);

        $inStockItems = Inventory::inStock()->get();

        $this->assertCount(1, $inStockItems);
    }
}
