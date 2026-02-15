<?php

declare(strict_types=1);

namespace Tests\Feature\Inventory;

use App\Modules\Inventory\Exceptions\InsufficientStockException;
use App\Modules\Inventory\Models\Inventory;
use App\Modules\Inventory\Services\InventoryService;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Product\Models\Product;
use App\Shared\Enums\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected InventoryService $inventoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inventoryService = app(InventoryService::class);
    }

    public function test_check_availability_returns_true_when_stock_sufficient(): void
    {
        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity_available' => 50,
        ]);

        $result = $this->inventoryService->checkAvailability($product->id, 10);

        $this->assertTrue($result);
    }

    public function test_check_availability_returns_false_when_stock_insufficient(): void
    {
        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity_available' => 5,
        ]);

        $result = $this->inventoryService->checkAvailability($product->id, 10);

        $this->assertFalse($result);
    }

    public function test_reserve_stock_for_order_success(): void
    {
        Event::fake();

        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity_available' => 100,
            'quantity_reserved' => 0,
        ]);

        $order = Order::factory()->create(['status' => OrderStatus::Pending]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        $this->inventoryService->reserveStockForOrder($order);

        $inventory->refresh();
        $this->assertEquals(90, $inventory->quantity_available);
        $this->assertEquals(10, $inventory->quantity_reserved);
    }

    public function test_reserve_stock_throws_exception_when_insufficient(): void
    {
        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity_available' => 5,
        ]);

        $order = Order::factory()->create(['status' => OrderStatus::Pending]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        $this->expectException(InsufficientStockException::class);
        $this->inventoryService->reserveStockForOrder($order);
    }

    public function test_restore_stock_for_cancelled_order(): void
    {
        Event::fake();

        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity_available' => 40,
            'quantity_reserved' => 10,
        ]);

        $order = Order::factory()->create(['status' => OrderStatus::Confirmed]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        $this->inventoryService->restoreStockForOrder($order);

        $inventory->refresh();
        $this->assertEquals(50, $inventory->quantity_available);
        $this->assertEquals(0, $inventory->quantity_reserved);
    }

    public function test_finalize_stock_for_shipped_order(): void
    {
        Event::fake();

        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity_available' => 40,
            'quantity_reserved' => 10,
        ]);

        $order = Order::factory()->create(['status' => OrderStatus::Processing]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        $this->inventoryService->finalizeStockForShippedOrder($order);

        $inventory->refresh();
        $this->assertEquals(40, $inventory->quantity_available);
        $this->assertEquals(0, $inventory->quantity_reserved);
    }

    public function test_adjust_inventory_increases_stock(): void
    {
        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity_available' => 50,
        ]);

        $this->inventoryService->adjustInventory($product->id, 20, 'restock');

        $inventory->refresh();
        $this->assertEquals(70, $inventory->quantity_available);
    }

    public function test_adjust_inventory_decreases_stock(): void
    {
        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity_available' => 50,
        ]);

        $this->inventoryService->adjustInventory($product->id, -10, 'damage');

        $inventory->refresh();
        $this->assertEquals(40, $inventory->quantity_available);
    }

    public function test_get_low_stock_products(): void
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        Inventory::factory()->lowStock()->create(['product_id' => $product1->id]);
        Inventory::factory()->highStock()->create(['product_id' => $product2->id]);

        $lowStockProducts = $this->inventoryService->getLowStockProducts();

        $this->assertCount(1, $lowStockProducts);
        $this->assertEquals($product1->id, $lowStockProducts->first()->product_id);
    }

    public function test_get_out_of_stock_products(): void
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        Inventory::factory()->outOfStock()->create(['product_id' => $product1->id]);
        Inventory::factory()->highStock()->create(['product_id' => $product2->id]);

        $outOfStockProducts = $this->inventoryService->getOutOfStockProducts();

        $this->assertCount(1, $outOfStockProducts);
        $this->assertEquals($product1->id, $outOfStockProducts->first()->product_id);
    }

    public function test_concurrent_orders_prevent_overselling(): void
    {
        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity_available' => 10, // Only 10 items
        ]);

        $order1 = Order::factory()->create(['status' => OrderStatus::Pending]);
        OrderItem::factory()->create([
            'order_id' => $order1->id,
            'product_id' => $product->id,
            'quantity' => 8,
        ]);

        $order2 = Order::factory()->create(['status' => OrderStatus::Pending]);
        OrderItem::factory()->create([
            'order_id' => $order2->id,
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        // First order should succeed
        $this->inventoryService->reserveStockForOrder($order1);

        // Second order should fail due to insufficient stock
        $this->expectException(InsufficientStockException::class);
        $this->inventoryService->reserveStockForOrder($order2);
    }
}
