# Inventory Module Documentation

## Overview

The Inventory module is a **production-grade inventory management system** that prevents overselling, manages stock reservations, and provides real-time inventory tracking integrated with the order lifecycle.

## Key Features

✅ **Prevents Overselling** - Uses row-level locking to prevent concurrent orders from depleting stock  
✅ **Stock Reservation** - Reserves stock when orders are confirmed  
✅ **Automatic Stock Management** - Integrates with order status changes  
✅ **Low Stock Alerts** - Monitors and alerts when stock falls below threshold  
✅ **Atomic Transactions** - All stock operations are wrapped in database transactions  
✅ **Cart Integration** - Validates stock availability in real-time during add-to-cart  
✅ **Admin Controls** - Complete admin interface for order processing and inventory adjustment

---

## Database Schema

### `inventories` Table

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `product_id` | bigint | Foreign key to products (unique) |
| `quantity_available` | integer | Stock available for purchase |
| `quantity_reserved` | integer | Stock reserved for confirmed orders |
| `low_stock_threshold` | integer | Alert threshold (default: 10) |
| `created_at` | timestamp | - |
| `updated_at` | timestamp | - |

---

## Stock Lifecycle

### 1️⃣ **Order Placed (Pending)**
- User clicks "Place Order"
- System validates stock availability
- If insufficient → Order rejected
- If sufficient → Order created with status `pending`
- **No stock changes yet**

### 2️⃣ **Order Confirmed (Admin Action)**
```php
// Admin confirms order
POST /admin/orders/{order}/confirm

// System automatically:
$inventory->quantity_available -= $orderQuantity;
$inventory->quantity_reserved += $orderQuantity;
```

### 3️⃣ **Order Shipped**
```php
// Admin marks as shipped
POST /admin/orders/{order}/shipped

// System automatically:
$inventory->quantity_reserved -= $orderQuantity;
// Stock physically left warehouse
```

### 4️⃣ **Order Cancelled**
```php
// Admin cancels order
POST /admin/orders/{order}/cancel

// System automatically:
$inventory->quantity_available += $orderQuantity;
$inventory->quantity_reserved -= $orderQuantity;
```

---

## Architecture

### **InventoryService** (Core Business Logic)

The `InventoryService` is the heart of the system with these methods:

```php
// Check if stock is available
checkAvailability(int $productId, int $quantity): bool

// Reserve stock for confirmed order (with locking)
reserveStockForOrder(Order $order): void

// Restore stock for cancelled order
restoreStockForOrder(Order $order): void

// Finalize stock when order ships
finalizeStockForShippedOrder(Order $order): void

// Admin manual adjustment
adjustInventory(int $productId, int $quantityChange, string $reason): Inventory

// Get low stock products
getLowStockProducts(): Collection

// Get out of stock products
getOutOfStockProducts(): Collection
```

---

## Event-Driven Architecture

### Events
- `OrderConfirmed` - Fired when admin confirms order
- `OrderCancelled` - Fired when order is cancelled
- `OrderShipped` - Fired when order ships
- `LowStockDetected` - Fired when stock falls below threshold
- `StockDepleted` - Fired when stock reaches zero

### Listeners
- `ReserveStockForConfirmedOrder` - Reserves stock automatically
- `RestoreStockForCancelledOrder` - Restores stock automatically
- `FinalizeStockForShippedOrder` - Finalizes inventory
- `NotifyAdminOfLowStock` - Logs low stock warnings
- `NotifyAdminOfStockDepletion` - Logs critical stock depletion

---

## Concurrency Control

### The Problem
```
Stock = 1
User A orders at 10:00:00.000
User B orders at 10:00:00.001
Both read stock = 1
Both orders succeed ❌ (OVERSELLING)
```

### The Solution
```php
DB::transaction(function () use ($order) {
    // SELECT FOR UPDATE - locks the row
    $inventory = Inventory::where('product_id', $productId)
        ->lockForUpdate()
        ->first();
    
    if ($inventory->quantity_available < $quantity) {
        throw new InsufficientStockException();
    }
    
    $inventory->quantity_available -= $quantity;
    $inventory->save();
});
```

**Result:** Only one order succeeds ✅

---

## Admin Routes

```php
// Inventory Dashboard
GET  /admin/inventory              → View low stock alerts
GET  /admin/inventory/manage       → Manage all inventory
POST /admin/inventory/{id}/adjust  → Manual stock adjustment

// Order Processing
POST   /admin/orders/{order}/confirm    → Confirm & reserve stock
POST   /admin/orders/{order}/processing → Mark as processing
POST   /admin/orders/{order}/shipped    → Ship & finalize stock
POST   /admin/orders/{order}/delivered  → Mark delivered
POST   /admin/orders/{order}/cancel     → Cancel & restore stock
PATCH  /admin/orders/{order}/status     → Update status with validation
```

---

## Cart Integration

The `CartService` now validates inventory in real-time:

```php
// When adding to cart
public function addToCart(int $userId, int $productId, int $quantity)
{
    // Check actual inventory, not just product.stock_quantity
    if (!$this->inventoryService->checkAvailability($productId, $quantity)) {
        throw new InvalidArgumentException('Insufficient stock');
    }
    
    // Add to cart...
}
```

---

## Testing

### Feature Tests
- `InventoryServiceTest` - Tests all service methods
- `OrderProcessingControllerTest` - Tests admin order processing

### Unit Tests
- `InventoryTest` - Tests model methods and scopes

### Run Tests
```bash
php artisan test --filter=Inventory
```

---

## Usage Examples

### Get Low Stock Products
```php
$inventoryService = app(InventoryService::class);
$lowStockProducts = $inventoryService->getLowStockProducts();

foreach ($lowStockProducts as $inventory) {
    echo "{$inventory->product->name}: {$inventory->quantity_available} left\n";
}
```

### Manually Adjust Inventory
```php
// Add 50 units
$inventoryService->adjustInventory(
    productId: 123,
    quantityChange: 50,
    reason: 'New shipment received'
);

// Remove 10 units
$inventoryService->adjustInventory(
    productId: 123,
    quantityChange: -10,
    reason: 'Damaged items'
);
```

### Check Stock Availability
```php
if ($inventoryService->checkAvailability($productId, 5)) {
    // Can purchase 5 units
} else {
    // Insufficient stock
}
```

---

## Data Flow Diagram

```
User Places Order (Pending)
        ↓
Admin Reviews Order
        ↓
Admin Confirms Order
        ↓
[Event: OrderConfirmed]
        ↓
[Listener: ReserveStockForConfirmedOrder]
        ↓
Inventory Updated:
    available: 100 → 90
    reserved:  0 → 10
        ↓
Admin Marks as Shipped
        ↓
[Event: OrderShipped]
        ↓
[Listener: FinalizeStockForShippedOrder]
        ↓
Inventory Updated:
    available: 90 (unchanged)
    reserved:  10 → 0
```

---

## Migration & Setup

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Seed Inventory for Existing Products
```bash
php artisan db:seed --class=InventorySeeder
```

### 3. Create Inventory for New Product (Automatic)
```php
// When creating a product, inventory is auto-created via ProductObserver
$product = Product::create([
    'name' => 'New Product',
    'stock_quantity' => 100,
    // ...
]);

// Inventory automatically created with:
// quantity_available = 100
// quantity_reserved = 0
// low_stock_threshold = 10
```

---

## Security

- ✅ All admin routes require authentication and `admin` middleware
- ✅ Form Request validation on all input
- ✅ Database transactions prevent partial updates
- ✅ Row-level locking prevents race conditions
- ✅ Comprehensive logging for audit trail

---

## Performance Considerations

- Indexed columns: `product_id`, `quantity_available`
- Composite index on `quantity_available` + `low_stock_threshold` for alerts
- `SELECT FOR UPDATE` only used during critical operations
- Eager loading used in inventory listings

---

## Future Enhancements

- 📧 Email notifications for low stock
- 📊 Inventory reports and analytics
- 📦 Multi-warehouse support
- 🔄 Automatic reorder points
- 📈 Stock movement history tracking
- 🔔 Real-time admin dashboard alerts

---

## Troubleshooting

### Stock not reserving?
Check if events are being dispatched:
```bash
php artisan event:list
```

### Stock reserved but order cancelled?
Stock is automatically restored. Check logs:
```bash
tail -f storage/logs/laravel.log | grep "Stock restored"
```

### Cart allows overselling?
Ensure InventoryService is injected in CartService constructor and inventory records exist for all products.

---

## Summary

This is a **production-ready inventory management system** that:
- Prevents overselling through row locking
- Manages stock lifecycle automatically
- Integrates seamlessly with cart and checkout
- Provides admin controls for order processing
- Includes comprehensive tests
- Follows Laravel 12 best practices

**You can now safely operate an e-commerce store without manual stock tracking.** 🚀
