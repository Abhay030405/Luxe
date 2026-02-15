<?php

declare(strict_types=1);

namespace Tests\Feature\Inventory;

use App\Models\User;
use App\Modules\Inventory\Models\Inventory;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Product\Models\Product;
use App\Shared\Enums\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderProcessingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_confirm_pending_order(): void
    {
        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity_available' => 100,
        ]);

        $order = Order::factory()->create(['status' => OrderStatus::Pending]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.orders.confirm', $order));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals(OrderStatus::Confirmed, $order->status);

        $inventory->refresh();
        $this->assertEquals(90, $inventory->quantity_available);
        $this->assertEquals(10, $inventory->quantity_reserved);
    }

    public function test_confirm_order_fails_with_insufficient_stock(): void
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

        $response = $this->actingAs($this->admin)
            ->post(route('admin.orders.confirm', $order));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $order->refresh();
        $this->assertEquals(OrderStatus::Pending, $order->status);
    }

    public function test_admin_can_mark_order_as_processing(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::Confirmed]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.orders.processing', $order));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals(OrderStatus::Processing, $order->status);
    }

    public function test_admin_can_mark_order_as_shipped(): void
    {
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

        $response = $this->actingAs($this->admin)
            ->post(route('admin.orders.shipped', $order));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals(OrderStatus::Shipped, $order->status);

        $inventory->refresh();
        $this->assertEquals(0, $inventory->quantity_reserved);
    }

    public function test_admin_can_cancel_confirmed_order(): void
    {
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

        $response = $this->actingAs($this->admin)
            ->post(route('admin.orders.cancel', $order));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals(OrderStatus::Cancelled, $order->status);

        $inventory->refresh();
        $this->assertEquals(50, $inventory->quantity_available);
        $this->assertEquals(0, $inventory->quantity_reserved);
    }

    public function test_non_admin_cannot_confirm_order(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $order = Order::factory()->create(['status' => OrderStatus::Pending]);

        $response = $this->actingAs($user)
            ->post(route('admin.orders.confirm', $order));

        $response->assertForbidden();
    }

    public function test_guest_cannot_confirm_order(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::Pending]);

        $response = $this->post(route('admin.orders.confirm', $order));

        $response->assertRedirect(route('login'));
    }
}
