# Order Module Documentation

## Overview
The Order Module manages the complete order lifecycle from checkout to delivery, including order placement, payment processing, status management, order tracking, and order history. It handles the critical business process of converting cart items into confirmed orders with proper inventory management and payment integration.

## Purpose
- Process checkout and order placement
- Manage order status workflow (Pending → Processing → Shipped → Delivered)
- Track orders with order numbers
- Handle order cancellations
- Display order history and details
- Integrate with payment and inventory modules
- Generate order confirmations and notifications
- Calculate order totals with tax and shipping

## Module Structure

```
Order/
├── Controllers/
│   ├── CheckoutController.php          # Checkout process and order placement
│   └── OrderController.php             # Order management (view, track, cancel)
├── DTOs/
│   ├── OrderDTO.php                    # Order summary data transfer object
│   └── OrderItemDTO.php                # Order item data transfer object
├── Events/
│   ├── OrderCancelled.php              # Triggered when order cancelled
│   ├── OrderPlaced.php                 # Triggered when new order created
│   ├── OrderShipped.php                # Triggered when order shipped
│   └── OrderStatusUpdated.php          # Triggered on status change
├── Listeners/
│   ├── DeductInventoryOnOrder.php      # Reduces stock when order placed
│   ├── RestoreInventoryOnCancel.php    # Restores stock when order cancelled
│   └── SendOrderConfirmationEmail.php  # Sends confirmation email
├── Models/
│   ├── Order.php                       # Order eloquent model
│   └── OrderItem.php                   # Order item eloquent model
├── Policies/
│   └── OrderPolicy.php                 # Authorization for order operations
├── Repositories/
│   ├── Contracts/
│   │   └── OrderRepositoryInterface.php
│   └── OrderRepository.php             # Order data access layer
├── Requests/
│   ├── CancelOrderRequest.php          # Validation for order cancellation
│   └── PlaceOrderRequest.php           # Validation for order placement
├── Services/
│   └── OrderService.php                # Order business logic
└── Routes.php                          # Order route definitions
```

## Routes

All order routes require authentication (`auth` middleware).

### Checkout Routes

- **GET** `/checkout` → `checkout.index` - Display checkout page
- **POST** `/checkout/place-order` → `checkout.place` - Submit and place order

### Order Management Routes

- **GET** `/orders` → `orders.index` - View order history (list of all user orders)
- **GET** `/orders/{id}` → `orders.show` - View single order details
- **POST** `/orders/{id}/cancel` → `orders.cancel` - Cancel an order
- **GET** `/orders/track/{orderNumber}` → `orders.track` - Track order by order number

## Database Tables

### Primary Table: orders
**Migration:** `2026_02_11_050134_create_orders_table.php`

**Columns:**
- `id` (bigint, primary key) - Order identifier
- `user_id` (bigint, foreign key) - Customer who placed order (references users.id)
- `order_number` (string, unique) - Human-readable order number (e.g., "ORD-20260210-001")
- `status` (enum) - Order status: `pending`, `processing`, `shipped`, `delivered`, `cancelled`
- `shipping_address_id` (bigint, foreign key) - Delivery address (references addresses.id)
- `billing_address_id` (bigint, foreign key) - Billing address (references addresses.id)
- `subtotal` (decimal 10,2) - Sum of all item prices
- `tax` (decimal 10,2) - Tax amount
- `shipping_cost` (decimal 10,2) - Delivery charges
- `discount` (decimal 10,2) - Applied discounts
- `total` (decimal 10,2) - Final amount to pay
- `payment_method` (string, nullable) - Payment method selected
- `payment_status` (enum) - Payment status: `pending`, `paid`, `failed`, `refunded`
- `notes` (text, nullable) - Customer order notes
- `cancelled_at` (timestamp, nullable) - When order was cancelled
- `cancelled_reason` (text, nullable) - Reason for cancellation
- `shipped_at` (timestamp, nullable) - When order was shipped
- `delivered_at` (timestamp, nullable) - When order was delivered
- `created_at` (timestamp) - When order was placed
- `updated_at` (timestamp) - Last modification

**Indexes:**
- Unique index on `order_number`
- Index on `user_id` for user order lookups
- Index on `status` for filtering orders
- Index on `created_at` for sorting

**Constraints:**
- All monetary amounts must be >= 0
- Foreign keys with cascade/restrict rules
- Status enum values validated

### Secondary Table: order_items
**Migration:** `2026_02_11_050144_create_order_items_table.php`

**Columns:**
- `id` (bigint, primary key) - Order item identifier
- `order_id` (bigint, foreign key) - Parent order (references orders.id)
- `product_id` (bigint, foreign key) - Product purchased (references products.id)
- `product_name` (string) - Product name snapshot
- `product_sku` (string, nullable) - SKU snapshot
- `quantity` (integer) - Number of items ordered
- `price` (decimal 10,2) - Unit price at order time
- `subtotal` (decimal 10,2) - price × quantity
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Purpose of Snapshots:**
- `product_name` and `product_sku` stored to preserve order details
- Even if product is deleted/changed later, order shows original data

**Indexes:**
- Index on `order_id` for fetching order items
- Index on `product_id` for sales reports

### Related Tables:
- **users** - Order placed by user
- **addresses** - Shipping and billing addresses
- **products** - Order items reference products
- **cart_items** - Source data for order creation
- **inventories** - Stock deducted on order placement
- **payments** - Payment transaction details (if Payment module integrated)

## Features & Functionality

### 1. Checkout Process

**Route:** `GET /checkout`

**Process:**
1. Check if user has items in cart
   - If empty: Redirect to cart with error
2. Fetch user's cart items with product details
3. Load user's saved addresses (shipping & billing)
4. Calculate order totals:
   - Subtotal = sum of cart items
   - Tax = subtotal × tax_rate
   - Shipping = calculate based on address/weight
   - Total = subtotal + tax + shipping - discount
5. Display checkout page

**Output:**
- Order summary with cart items
- Shipping address selector (existing addresses + add new)
- Billing address selector (or "same as shipping" checkbox)
- Payment method selection (COD, Credit Card, UPI, etc.)
- Order notes textarea
- Order total breakdown
- "Place Order" button
- Terms and conditions checkbox

**Validation:**
- Must have items in cart
- Must select shipping address
- Must select billing address
- Must agree to terms
- Payment method required

### 2. Place Order

**Route:** `POST /checkout/place-order`

**Validation Rules:**
```php
'shipping_address_id' => 'required|exists:addresses,id',
'billing_address_id' => 'required|exists:addresses,id',
'payment_method' => 'required|string|in:cod,credit_card,debit_card,upi,net_banking',
'notes' => 'nullable|string|max:500',
'terms_accepted' => 'required|accepted'
```

**Process:**
1. Validate request data
2. Verify user owns selected addresses
3. Fetch cart items
4. **Stock Validation:**
   - For each cart item, check inventory
   - If any item out of stock: Abort with error
5. **Calculate Order Totals:**
   - Subtotal from cart items
   - Apply tax rate
   - Calculate shipping cost
   - Apply discount (if any)
   - Calculate final total
6. **Database Transaction:**
   ```php
   DB::transaction(function () {
       // Create order record
       $order = Order::create([...]);
       
       // Create order items
       foreach ($cartItems as $cartItem) {
           OrderItem::create([
               'order_id' => $order->id,
               'product_id' => $cartItem->product_id,
               'product_name' => $cartItem->product->name,
               'quantity' => $cartItem->quantity,
               'price' => $cartItem->price,
               'subtotal' => $cartItem->quantity * $cartItem->price,
           ]);
       }
       
       // Deduct inventory
       // Clear cart
   });
   ```
7. **Fire Events:**
   - `OrderPlaced` - Sends confirmation email, updates analytics
   - `DeductInventoryOnOrder` - Reduces stock quantities
8. Process payment (if online payment selected)
9. Clear user's cart
10. Redirect to order success page

**Response:**
- Success: Redirect to `/orders/{id}` with message "Order placed successfully! Order #ORD-xxx"
- Failure: Redirect back with errors

**Error Scenarios:**
- Cart empty
- Product out of stock
- Address not found
- Payment failed
- Database error (transaction rolled back)

### 3. Order History

**Route:** `GET /orders`

**Process:**
1. Fetch all orders for authenticated user
2. Order by created_at DESC (newest first)
3. Eager load order items and product images
4. Paginate results (10 per page)
5. Display orders list

**Output:**
- Table/list of orders showing:
  - Order number (clickable to details)
  - Order date
  - Status badge (color-coded)
  - Total amount
  - Payment status
  - Number of items
  - Quick actions (View, Cancel if pending)
- Pagination controls
- Filter by status (optional)
- Search by order number (optional)
- Empty state if no orders

**Status Badge Colors:**
- Pending: Yellow/Warning
- Processing: Blue/Info
- Shipped: Purple
- Delivered: Green/Success
- Cancelled: Red/Danger

### 4. Order Details

**Route:** `GET /orders/{id}`

**Process:**
1. Find order by ID
2. Authorize user owns this order
3. Eager load:
   - Order items with product images
   - Shipping address
   - Billing address
4. Display detailed order page

**Output:**
- Order header:
  - Order number
  - Order date
  - Status badge
  - Payment status
  - Order timeline (placed → processing → shipped → delivered)
- Shipping information:
  - Delivery address
  - Estimated delivery date
  - Tracking number (if shipped)
- Billing information:
  - Billing address
  - Payment method
- Order items table:
  - Product image
  - Product name (linked)
  - SKU
  - Quantity
  - Unit price
  - Subtotal
- Order summary:
  - Subtotal
  - Tax
  - Shipping
  - Discount
  - Total
- Customer notes (if provided)
- Action buttons:
  - "Cancel Order" (if status = pending)
  - "Track Order"
  - "Print Invoice"
  - "Download Invoice PDF"
  - "Contact Support"

### 5. Cancel Order

**Route:** `POST /orders/{id}/cancel`

**Validation Rules:**
```php
'reason' => 'required|string|max:500'
```

**Process:**
1. Find order by ID
2. Authorize user owns this order
3. **Check if cancellable:**
   - Status must be `pending` or `processing`
   - Cannot cancel if `shipped` or `delivered`
4. **Database Transaction:**
   ```php
   DB::transaction(function () use ($order, $reason) {
       // Update order status
       $order->update([
           'status' => 'cancelled',
           'cancelled_at' => now(),
           'cancelled_reason' => $reason,
       ]);
       
       // Restore inventory
       // Initiate refund (if payment made)
   });
   ```
5. **Fire Events:**
   - `OrderCancelled` - Triggers refund, email notification
   - `RestoreInventoryOnCancel` - Returns stock to inventory
6. Redirect back with success message

**Response:**
- Success: "Order cancelled successfully. Refund will be processed in 3-5 business days."
- Error: "Cannot cancel order. It has already been shipped."

**Business Rules:**
- Pending orders: Cancel immediately
- Processing orders: Cancel with approval
- Shipped orders: Cannot cancel (return instead)
- Delivered orders: Cannot cancel (return only)

### 6. Track Order

**Route:** `GET /orders/track/{orderNumber}`

**Process:**
1. Find order by order_number
2. Authorize user owns this order
3. Fetch order timeline and status
4. Display tracking page

**Output:**
- Order number and current status
- Progress timeline:
  ```
  ✓ Order Placed (10 Feb 2026, 10:30 AM)
  ✓ Order Confirmed (10 Feb 2026, 11:00 AM)
  ✓ Shipped (11 Feb 2026, 9:00 AM) - Tracking: ABC123456
  ○ Out for Delivery
  ○ Delivered
  ```
- Courier information (if shipped)
- Tracking number (external link to courier)
- Estimated delivery date
- Contact information

**Tracking Statuses:**
- **Pending:** Order received, payment pending
- **Processing:** Payment confirmed, preparing order
- **Shipped:** Order dispatched with courier
- **Out for Delivery:** Order with delivery agent
- **Delivered:** Order received by customer
- **Cancelled:** Order cancelled

## Output Examples

### Checkout Page (`/checkout`)

**Layout:**
```
Cart Summary               Shipping Details
--------------             -----------------
Product A × 2 = ₹2,000    [Select Address ▾]
Product B × 1 = ₹1,500    ○ Address 1 (default)
                          ○ Address 2
Subtotal:    ₹3,500       + Add New Address
Tax (18%):     ₹630
Shipping:      ₹100       Billing Address
              ------      ----------------
Total:       ₹4,230      ☑ Same as shipping
                          
Payment Method            Order Notes
--------------            -----------
○ Cash on Delivery        [Optional notes...]
○ Credit/Debit Card
● UPI                     ☑ I agree to terms

                          [ Place Order ]
```

### Order Confirmation (`/orders/{id}`)

```
Order #ORD-20260210-001          Status: Processing
Placed on: February 10, 2026                    Payment: Paid

Progress:
✓ Order Placed → ✓ Confirmed → ● Processing → ○ Shipped → ○ Delivered

Delivery Address               Billing Address
-----------------             -----------------
John Doe                      Same as shipping
123 Main Street
Mumbai, MH 400001
Phone: +91 98765 43210

Order Items
-----------
Product A × 2    ₹1,000 each    ₹2,000
Product B × 1    ₹1,500 each    ₹1,500

                 Subtotal:      ₹3,500
                 Tax (18%):       ₹630
                 Shipping:        ₹100
                 ──────────────────────
                 Total:         ₹4,230

[ Cancel Order ]  [ Track Order ]  [ Download Invoice ]
```

## How to Use

### For End Users

#### Placing an Order

1. **Add Products to Cart:**
   - Browse products
   - Add desired items to cart
   - Adjust quantities

2. **Proceed to Checkout:**
   ```
   Cart → Click "Proceed to Checkout"
   ```

3. **Fill Checkout Details:**
   - Select or add shipping address
   - Select billing address
   - Choose payment method
   - Add order notes (optional)
   - Accept terms and conditions

4. **Place Order:**
   - Review order summary
   - Click "Place Order"
   - Complete payment (if online)
   - See order confirmation

5. **Receive Confirmation:**
   - Order success page displayed
   - Confirmation email sent
   - Order number provided

#### Viewing Orders

1. **Access Order History:**
   ```
   My Account → Orders
   Or visit: /orders
   ```

2. **Filter/Search Orders:**
   - Filter by status
   - Search by order number
   - Sort by date

3. **View Order Details:**
   - Click order number
   - See complete order information
   - Track order status

#### Tracking an Order

1. **From Order Details:**
   ```
   Orders → Select Order → Click "Track Order"
   ```

2. **By Order Number:**
   ```
   Visit: /orders/track/ORD-20260210-001
   ```

3. **View Progress:**
   - See order timeline
   - Check current status
   - View estimated delivery

#### Cancelling an Order

1. **Navigate to Order:**
   ```
   Orders → Select Order
   ```

2. **Check if Cancellable:**
   - "Cancel Order" button visible if pending/processing
   - Not visible if shipped/delivered

3. **Request Cancellation:**
   - Click "Cancel Order"
   - Provide cancellation reason
   - Confirm cancellation

4. **Confirmation:**
   - Order status updated to "Cancelled"
   - Refund initiated (if paid)
   - Confirmation email sent

### For Developers

#### Creating an Order Programmatically

```php
use App\Modules\Order\Services\OrderService;
use App\Modules\Cart\Services\CartService;

$orderService = app(OrderService::class);
$cartService = app(CartService::class);

// Get cart items for user
$cartItems = $cartService->getCartItems(auth()->id());

// Create order from cart
$order = $orderService->createOrder([
    'user_id' => auth()->id(),
    'cart_items' => $cartItems,
    'shipping_address_id' => $shippingAddressId,
    'billing_address_id' => $billingAddressId,
    'payment_method' => 'upi',
    'notes' => 'Please call before delivery',
]);

// Clear cart after successful order
$cartService->clearCart(auth()->id());
```

#### Updating Order Status

```php
use App\Modules\Order\Models\Order;
use App\Modules\Order\Events\OrderStatusUpdated;

$order = Order::find($orderId);

$order->update(['status' => 'shipped', 'shipped_at' => now()]);

event(new OrderStatusUpdated($order, 'processing', 'shipped'));
```

#### Calculating Order Totals

```php
$orderService = app(OrderService::class);

$totals = $orderService->calculateTotals($cartItems, $shippingAddress);

/*
Returns:
[
    'subtotal' => 3500.00,
    'tax' => 630.00,
    'shipping' => 100.00,
    'discount' => 0.00,
    'total' => 4230.00,
]
*/
```

#### Fetching User Orders

```php
// Get all orders for a user
$orders = Order::where('user_id', auth()->id())
    ->with(['items.product', 'shippingAddress'])
    ->latest()
    ->paginate(10);

// Get orders by status
$pendingOrders = Order::where('user_id', auth()->id())
    ->where('status', 'pending')
    ->get();
```

#### Using OrderDTO

```php
$orderDTO = $orderService->getOrderDetails($orderId);

echo $orderDTO->orderNumber;      // ORD-20260210-001
echo $orderDTO->status;           // processing
echo $orderDTO->total;            // 4230.00
echo $orderDTO->itemCount;        // 3

foreach ($orderDTO->items as $item) {
    echo $item->productName;
    echo $item->quantity;
    echo $item->price;
    echo $item->subtotal;
}
```

## Events & Listeners

### OrderPlaced Event
**Triggered:** When new order successfully created

**Data:**
- order (Order model)
- user (User model)

**Listeners:**
- `SendOrderConfirmationEmail` - Emails customer
- `DeductInventoryOnOrder` - Reduces stock
- Update analytics
- Notify admin

### OrderStatusUpdated Event
**Triggered:** Whenever order status changes

**Data:**
- order (Order model)
- oldStatus (string)
- newStatus (string)

**Listeners:**
- Send status update email
- Log status change
- Update tracking info

### OrderShipped Event
**Triggered:** When order status becomes "shipped"

**Data:**
- order (Order model)
- trackingNumber (string)
- courier (string)

**Listeners:**
- Send shipping notification email
- Update tracking URL
- Set expected delivery date

### OrderCancelled Event
**Triggered:** When order is cancelled

**Data:**
- order (Order model)
- reason (string)
- cancelledBy (user/admin/system)

**Listeners:**
- `RestoreInventoryOnCancel` - Returns stock to inventory
- Initiate refund process
- Send cancellation email
- Update order metrics

## Business Logic

### Order Number Generation
```php
// Format: ORD-YYYYMMDD-XXX
// Example: ORD-20260210-001

$orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad($dailyCount, 3, '0', STR_PAD_LEFT);
```

### Status Workflow
```
pending → processing → shipped → delivered
   ↓
cancelled (only from pending or processing)
```

**Status Transitions:**
- Pending: Order placed, awaiting payment/confirmation
- Processing: Payment confirmed, order being prepared
- Shipped: Order handed to courier
- Delivered: Order received by customer
- Cancelled: Order cancelled by user/admin

**Allowed Transitions:**
- pending → processing, cancelled
- processing → shipped, cancelled
- shipped → delivered
- delivered → (final state)
- cancelled → (final state)

### Price Locking
Order items store:
- `product_name` - Product name at order time
- `price` - Unit price at order time
- `product_sku` - SKU snapshot

This ensures:
- Order history remains accurate
- Price changes don't affect past orders
- Product deletion doesn't break order history

### Inventory Deduction
On order placement:
```php
// For each order item
Inventory::where('product_id', $item->product_id)
    ->decrement('quantity_available', $item->quantity);
```

On order cancellation:
```php
// For each order item
Inventory::where('product_id', $item->product_id)
    ->increment('quantity_available', $item->quantity);
```

### Tax Calculation
```php
$taxRate = config('app.tax_rate', 0.18); // 18% default
$taxAmount = $subtotal * $taxRate;
```

### Shipping Calculation
Based on:
- Shipping address location
- Total weight of items
- Shipping method selected
- Flat rate or dynamic calculation

## Security & Validation

### Authorization
- Users can only access their own orders
- Order queries filtered by `user_id`
- OrderPolicy enforces ownership
- Admin can view all orders

### Validation
- Address ownership verified
- Stock availability checked before order
- Payment validation
- Amount calculations server-side (never trust client)

### Data Integrity
- Database transactions for order creation
- Atomic inventory updates
- Foreign key constraints
- Money calculations using decimals (not floats)

## Integration with Other Modules

### Dependencies:
- **Auth Module** - User authentication
- **Cart Module** - Source for order items
- **Product Module** - Product information
- **Inventory Module** - Stock management
- **Payment Module** - Payment processing
- **User Module** - Addresses

### Used By:
- **Payment Module** - Payment confirmation updates order
- **Admin Module** - Order management dashboard

## Common Issues & Solutions

### Issue: "Order placed but cart not cleared"
**Cause:** Cart clearing code after transaction
**Solution:** Clear cart inside transaction or use event listener

### Issue: "Stock oversold"
**Cause:** Race condition in inventory checks
**Solution:** Use database-level locking or atomic decrement

### Issue: "User can cancel shipped order"
**Cause:** Missing status validation
**Solution:** Check order status before allowing cancel

### Issue: "Order total mismatch"
**Cause:** Client-side tampering or stale prices
**Solution:** Always recalculate totals server-side

## Testing

### Feature Tests
```bash
# Run order tests
php artisan test tests/Feature/Order
```

**Test Coverage:**
- Place order from cart
- Calculate order totals
- Cancel pending order
- Cannot cancel shipped order
- Inventory deduction on order
- Inventory restoration on cancel
- Order authorization
- Order status workflow

### Manual Testing Checklist
- [ ] Place order with COD
- [ ] Place order with online payment
- [ ] View order history
- [ ] View order details
- [ ] Cancel pending order
- [ ] Cannot cancel shipped order
- [ ] Track order by number
- [ ] Stock reduces after order
- [ ] Stock restores after cancel
- [ ] Email sent on order placed
- [ ] Email sent on order shipped

## Performance Optimization

### Database Optimization
- Eager load relationships: `with(['items.product', 'shippingAddress'])`
- Index on user_id, status, order_number
- Paginate order history
- Cache order counts

### Query Optimization
```php
// Good: Single query with eager loading
$orders = Order::with('items.product.images')->get();

// Bad: N+1 queries
$orders = Order::all();
foreach ($orders as $order) {
    foreach ($order->items as $item) {
        $product = $item->product; // N+1 query
    }
}
```

## Future Enhancements
- Guest checkout (save order before account creation)
- Multiple payment methods per order
- Split shipments (partial delivery)
- Order invoice PDF generation
- Email/SMS order status updates
- Return/exchange system
- Order reviews and ratings
- Reorder functionality
- Subscription/recurring orders
- Multi-currency support
- International shipping
- Order gift wrapping
- Bulk order import
- Advanced tracking with map

---

**Module Version:** 1.0  
**Last Updated:** February 2026  
**Maintained By:** Development Team
