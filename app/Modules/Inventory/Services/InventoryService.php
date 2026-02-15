<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Services;

use App\Modules\Inventory\Events\LowStockDetected;
use App\Modules\Inventory\Events\OrderCancelled;
use App\Modules\Inventory\Events\OrderConfirmed;
use App\Modules\Inventory\Events\OrderShipped;
use App\Modules\Inventory\Events\StockDepleted;
use App\Modules\Inventory\Exceptions\InsufficientStockException;
use App\Modules\Inventory\Models\Inventory;
use App\Modules\Order\Models\Order;
use App\Modules\Product\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Check if sufficient stock is available for purchase.
     */
    public function checkAvailability(int $productId, int $quantity): bool
    {
        $inventory = Inventory::where('product_id', $productId)->first();

        if (! $inventory) {
            // Fallback to product's stock_quantity if no inventory record exists
            $product = Product::find($productId);

            if (! $product) {
                return false;
            }

            return $product->stock_quantity >= $quantity;
        }

        return $inventory->hasAvailableStock($quantity);
    }

    /**
     * Reserve stock when order is confirmed (atomic operation with row locking).
     *
     *
     * @throws InsufficientStockException
     */
    public function reserveStockForOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                // Use SELECT FOR UPDATE to prevent race conditions
                $inventory = Inventory::where('product_id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (! $inventory) {
                    throw new InsufficientStockException(
                        "No inventory record found for product ID {$item->product_id}"
                    );
                }

                // Check if sufficient stock is available
                if (! $inventory->hasAvailableStock($item->quantity)) {
                    throw new InsufficientStockException(
                        "Insufficient stock for product '{$item->product_name}'. Available: {$inventory->quantity_available}, Requested: {$item->quantity}"
                    );
                }

                // Move stock from available to reserved
                $inventory->quantity_available -= $item->quantity;
                $inventory->quantity_reserved += $item->quantity;
                $inventory->save();

                // Fire events for monitoring
                if ($inventory->isOutOfStock()) {
                    event(new StockDepleted($inventory));
                } elseif ($inventory->isLowStock()) {
                    event(new LowStockDetected($inventory));
                }

                Log::info('Stock reserved', [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'order_id' => $order->id,
                    'available_after' => $inventory->quantity_available,
                    'reserved_after' => $inventory->quantity_reserved,
                ]);
            }
        });

        event(new OrderConfirmed($order));
    }

    /**
     * Restore stock when order is cancelled (atomic operation).
     */
    public function restoreStockForOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (! $inventory) {
                    Log::warning('Cannot restore stock - inventory record not found', [
                        'product_id' => $item->product_id,
                        'order_id' => $order->id,
                    ]);

                    continue;
                }

                // Restore stock from reserved back to available
                $inventory->quantity_available += $item->quantity;
                $inventory->quantity_reserved -= $item->quantity;

                // Ensure reserved doesn't go negative
                if ($inventory->quantity_reserved < 0) {
                    $inventory->quantity_reserved = 0;
                }

                $inventory->save();

                Log::info('Stock restored', [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'order_id' => $order->id,
                    'available_after' => $inventory->quantity_available,
                    'reserved_after' => $inventory->quantity_reserved,
                ]);
            }
        });

        event(new OrderCancelled($order));
    }

    /**
     * Finalize stock deduction when order is shipped.
     */
    public function finalizeStockForShippedOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (! $inventory) {
                    Log::warning('Cannot finalize stock - inventory record not found', [
                        'product_id' => $item->product_id,
                        'order_id' => $order->id,
                    ]);

                    continue;
                }

                // Reduce reserved stock (item has physically left warehouse)
                $inventory->quantity_reserved -= $item->quantity;

                // Ensure reserved doesn't go negative
                if ($inventory->quantity_reserved < 0) {
                    $inventory->quantity_reserved = 0;
                }

                $inventory->save();

                Log::info('Stock finalized for shipped order', [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'order_id' => $order->id,
                    'reserved_after' => $inventory->quantity_reserved,
                ]);
            }
        });

        event(new OrderShipped($order));
    }

    /**
     * Manually adjust inventory (admin function).
     *
     * @param  int  $quantityChange  Positive to add, negative to remove
     */
    public function adjustInventory(int $productId, int $quantityChange, string $reason = 'manual_adjustment'): Inventory
    {
        return DB::transaction(function () use ($productId, $quantityChange, $reason) {
            $inventory = Inventory::where('product_id', $productId)
                ->lockForUpdate()
                ->firstOrFail();

            $previousAvailable = $inventory->quantity_available;
            $inventory->quantity_available += $quantityChange;

            // Don't allow negative available stock
            if ($inventory->quantity_available < 0) {
                $inventory->quantity_available = 0;
            }

            $inventory->save();

            Log::info('Inventory manually adjusted', [
                'product_id' => $productId,
                'change' => $quantityChange,
                'previous' => $previousAvailable,
                'new' => $inventory->quantity_available,
                'reason' => $reason,
            ]);

            // Fire events
            if ($inventory->isOutOfStock()) {
                event(new StockDepleted($inventory));
            } elseif ($inventory->isLowStock()) {
                event(new LowStockDetected($inventory));
            }

            return $inventory;
        });
    }

    /**
     * Create or update inventory record for a product.
     */
    public function createOrUpdateInventory(
        int $productId,
        int $quantityAvailable,
        int $lowStockThreshold = 10
    ): Inventory {
        return Inventory::updateOrCreate(
            ['product_id' => $productId],
            [
                'quantity_available' => $quantityAvailable,
                'low_stock_threshold' => $lowStockThreshold,
            ]
        );
    }

    /**
     * Get all products with low stock.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLowStockProducts()
    {
        return Inventory::with('product')
            ->lowStock()
            ->get();
    }

    /**
     * Get all products that are out of stock.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOutOfStockProducts()
    {
        return Inventory::with('product')
            ->outOfStock()
            ->get();
    }

    /**
     * Sync product stock_quantity with inventory available stock.
     * This is for backward compatibility if needed.
     */
    public function syncProductStockQuantity(Product $product): void
    {
        $inventory = $product->inventory;

        if ($inventory) {
            $product->stock_quantity = $inventory->quantity_available;
            $product->save();
        }
    }
}
