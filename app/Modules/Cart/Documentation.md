# Cart Module Documentation

## Overview
The Cart Module manages shopping cart functionality, allowing authenticated users to add products, update quantities, remove items, and proceed to checkout. It provides both traditional form-based and AJAX-based interactions for a seamless shopping experience.

## Purpose
- Store product selections before checkout
- Calculate cart totals with pricing and taxes
- Validate product availability and stock  
- Persist cart data across sessions
- Provide quick add-to-cart and update functionality
- Support cart item quantity management
- Clear cart after successful checkout

## Module Structure

```
Cart/
├── Controllers/
│   └── CartController.php              # Cart operations (view, add, update, delete)
├── DTOs/
│   ├── CartDTO.php                     # Cart summary data transfer object
│   └── CartItemDTO.php                 # Cart item data transfer object
├── Events/
│   ├── CartCleared.php                 # Triggered when cart is emptied
│   ├── CartItemAdded.php               # Triggered when item added
│   ├── CartItemRemoved.php             # Triggered when item removed
│   └── CartItemUpdated.php             # Triggered when quantity changed
├── Listeners/
│   └── ClearCartAfterOrder.php         # Clears cart after successful order
├── Models/
│   └── CartItem.php                    # Cart item eloquent model
├── Policies/
│   └── CartPolicy.php                  # Authorization for cart operations
├── Repositories/
│   ├── Contracts/
│   │   └── CartRepositoryInterface.php
│   └── CartRepository.php              # Cart data access layer
├── Requests/
│   ├── AddToCartRequest.php            # Validation for adding items
│   └── UpdateCartRequest.php           # Validation for updating quantities
├── Services/
│   └── CartService.php                 # Cart business logic
└── Routes.php                          # Cart route definitions
```

## Routes

All cart routes require authentication (`auth` middleware).

### Standard Cart Routes

- **GET** `/cart` → `cart.index` - View shopping cart
- **POST** `/cart` → `cart.store` - Add item to cart
- **PUT** `/cart/{cartItemId}` → `cart.update` - Update item quantity
- **DELETE** `/cart/{cartItemId}` → `cart.destroy` - Remove item from cart
- **DELETE** `/cart` → `cart.clear` - Clear entire cart

### AJAX Cart Routes (for dynamic updates)

Prefix: `/ajax/cart`, Name prefix: `cart.ajax.`

- **POST** `/ajax/cart` → `cart.ajax.store` - Add item (returns JSON)
- **PUT** `/ajax/cart/{cartItemId}` → `cart.ajax.update` - Update item (returns JSON)
- **DELETE** `/ajax/cart/{cartItemId}` → `cart.ajax.destroy` - Remove item (returns JSON)
- **GET** `/ajax/cart/count` → `cart.ajax.count` - Get cart item count (returns JSON)

## Database Tables

### Primary Table: cart_items
**Migration:** `2026_02_11_045444_create_cart_items_table.php`

**Columns:**
- `id` (bigint, primary key) - Cart item identifier
- `user_id` (bigint, foreign key) - Owner of cart (references users.id)
- `product_id` (bigint, foreign key) - Product in cart (references products.id)
- `quantity` (integer) - Number of items
- `price` (decimal 10,2) - Unit price at time of adding
- `created_at` (timestamp) - When item was added
- `updated_at` (timestamp) - Last modification

**Indexes:**
- Index on `user_id` for fast user cart lookups
- Index on `product_id` for stock validation
- Unique composite index on (`user_id`, `product_id`) - prevents duplicate items

**Constraints:**
- `quantity` must be greater than 0
- Foreign key cascade on user deletion (deletes cart)
- Foreign key cascade on product deletion (removes from carts)

### Related Tables:
- **users** - Cart belongs to authenticated user
- **products** - Cart items reference products
- **product_images** - For displaying product thumbnails
- **inventories** - For stock availability checks

## Features & Functionality

### 1. View Shopping Cart

**Route:** `GET /cart`

**Process:**
1. Fetch all cart items for authenticated user
2. Eager load product details (name, images, current price)
3. Calculate per-item subtotals
4. Calculate cart totals (subtotal, tax, shipping, grand total)
5. Check stock availability for each item
6. Display cart page with item list and totals

**Output:**
- Product thumbnail images
- Product names with links
- Unit prices (current vs cart price comparison)
- Quantity selectors
- Per-item subtotals
- Remove item buttons
- Cart summary with totals
- "Continue Shopping" and "Proceed to Checkout" buttons
- Out-of-stock warnings
- Empty cart message if no items

### 2. Add to Cart

**Routes:**
- Standard: `POST /cart`
- AJAX: `POST /ajax/cart`

**Validation Rules:**
```php
'product_id' => 'required|exists:products,id',
'quantity' => 'required|integer|min:1|max:100'
```

**Process:**
1. Validate request data
2. Check if product exists and is active
3. Verify stock availability (quantity_available >= quantity)
4. Check if item already in cart
   - If yes: Update quantity (existing + new)
   - If no: Create new cart item
5. Store current product price
6. Fire `CartItemAdded` event
7. Redirect to cart or return JSON

**Stock Validation:**
- Compares requested quantity with `inventory.quantity_available`
- Prevents adding more than available stock
- Shows error: "Only X items available in stock"

**Response:**
- Standard: Redirect to `/cart` with success message
- AJAX: JSON with cart count and success message

### 3. Update Cart Quantity

**Routes:**
- Standard: `PUT /cart/{cartItemId}`
- AJAX: `PUT /ajax/cart/{cartItemId}`

**Validation Rules:**
```php
'quantity' => 'required|integer|min:1|max:100'
```

**Process:**
1. Find cart item by ID
2. Authorize user owns this cart item
3. Validate new quantity against stock
4. Update quantity in database
5. Fire `CartItemUpdated` event
6. Recalculate cart totals
7. Redirect or return JSON

**Constraints:**
- Quantity must be at least 1
- Quantity cannot exceed available stock
- Maximum 100 per item (configurable)

**Response:**
- Standard: Redirect back with success message
- AJAX: JSON with updated totals

### 4. Remove from Cart

**Routes:**
- Standard: `DELETE /cart/{cartItemId}`
- AJAX: `DELETE /ajax/cart/{cartItemId}`

**Process:**
1. Find cart item by ID
2. Authorize user owns this cart item
3. Delete cart item
4. Fire `CartItemRemoved` event
5. Recalculate cart totals
6. Redirect or return JSON

**Response:**
- Standard: Redirect back with message "Item removed from cart"
- AJAX: JSON with new cart count and totals

### 5. Clear Cart

**Route:** `DELETE /cart`

**Process:**
1. Delete all cart items for authenticated user
2. Fire `CartCleared` event
3. Redirect to cart page with message

**Use Cases:**
- User wants to start fresh
- Automatic clearing after order placed
- Session cleanup

**Response:**
- Redirect to `/cart` with message "Cart cleared successfully"
- Display empty cart state

### 6. Get Cart Count (AJAX)

**Route:** `GET /ajax/cart/count`

**Process:**
1. Count total items in user's cart
2. Return JSON response

**Response:**
```json
{
    "count": 3,
    "success": true
}
```

**Usage:**
- Update cart badge in navigation
- Real-time cart count updates
- After any cart operation

## Output

### Cart Page (`/cart`)

**When Empty:**
- Large empty cart icon
- Message: "Your cart is empty"
- "Continue Shopping" button
- Link to product page

**When Has Items:**
- Table/grid of cart items showing:
  - Product thumbnail (clickable to product page)
  - Product name
  - Unit price
  - Quantity selector (- / input / + buttons)
  - Subtotal (price × quantity)
  - Remove button (trash icon)
- Cart summary sidebar/section:
  - Subtotal: Sum of all items
  - Shipping: Calculated or "Free"
  - Tax: Calculated percentage
  - Total: Grand total
  - "Update Cart" button
  - "Proceed to Checkout" button
- Continue Shopping link
- Stock warnings for limited items

### AJAX Responses

**Add to Cart Success:**
```json
{
    "success": true,
    "message": "Product added to cart!",
    "cart_count": 3,
    "cart_subtotal": "₹2,500.00"
}
```

**Stock Error:**
```json
{
    "success": false,
    "message": "Only 5 items available in stock",
    "available_quantity": 5
}
```

**Update Success:**
```json
{
    "success": true,
    "message": "Cart updated successfully",
    "cart_count": 4,
    "item_subtotal": "₹1,000.00",
    "cart_subtotal": "₹3,500.00",
    "cart_total": "₹3,850.00"
}
```

## How to Use

### For End Users

#### Adding Products to Cart

1. **From Product Page:**
   - Select quantity (using +/- buttons or input)
   - Click "Add to Cart" button
   - See success message
   - Cart count badge updates

2. **Quick Add (Product List):**
   - Click "Add to Cart" icon on product card
   - Default quantity: 1
   - Product added instantly
   - Success notification appears

#### Viewing Cart

1. **Navigate to Cart:**
   ```
   Click cart icon in navigation
   Or visit: /cart
   ```

2. **Review Items:**
   - Check product details
   - Verify quantities
   - See price breakdown
   - Check stock availability

#### Updating Quantities

1. **Using Quantity Selector:**
   - Click "-" to decrease
   - Click "+" to increase
   - Or type quantity directly
   - Click "Update Cart" button

2. **AJAX Update (auto-save):**
   - Change quantity
   - Cart updates automatically
   - No page reload needed
   - See updated totals instantly

#### Removing Items

1. **Individual Item:**
   - Click trash/remove icon next to item
   - Confirm if prompted
   - Item removed instantly
   - Totals recalculated

2. **Clear All:**
   - Click "Clear Cart" button
   - Confirm action
   - All items removed
   - Empty cart message shown

#### Proceed to Checkout

1. **Click Checkout Button:**
   - Must have items in cart
   - Must be logged in
   - Redirects to checkout page
   - Cart locked during checkout

### For Developers

#### Add to Cart from Code

```php
use App\Modules\Cart\Services\CartService;

$cartService = app(CartService::class);

// Add item to cart
$cartItem = $cartService->addToCart(
    userId: auth()->id(),
    productId: $productId,
    quantity: $quantity
);

// Update quantity
$cartService->updateQuantity($cartItemId, $newQuantity);

// Remove item
$cartService->removeItem($cartItemId);

// Clear cart
$cartService->clearCart(auth()->id());

// Get cart items
$items = $cartService->getCartItems(auth()->id());

// Get cart totals
$totals = $cartService->getCartTotals(auth()->id());
```

#### Using CartDTO

```php
$cartDTO = $cartService->getCart(auth()->id());

echo $cartDTO->itemCount;        // Total number of items
echo $cartDTO->subtotal;          // Subtotal amount
echo $cartDTO->tax;              // Tax amount
echo $cartDTO->shipping;         // Shipping cost
echo $cartDTO->total;            // Grand total

foreach ($cartDTO->items as $item) {
    echo $item->productName;
    echo $item->quantity;
    echo $item->price;
    echo $item->subtotal;
}
```

#### AJAX Implementation Example

```javascript
// Add to cart with AJAX
async function addToCart(productId, quantity) {
    const response = await fetch('/ajax/cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    });
    
    const data = await response.json();
    
    if (data.success) {
        // Update cart badge
        document.getElementById('cart-count').textContent = data.cart_count;
        // Show success notification
        showNotification(data.message);
    }
}

// Update cart item
async function updateCartItem(cartItemId, quantity) {
    const response = await fetch(`/ajax/cart/${cartItemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ quantity: quantity })
    });
    
    const data = await response.json();
    // Update UI with new totals
}
```

## Events & Listeners

### CartItemAdded Event
**Triggered:** When product added to cart

**Data:**
- userId
- productId
- quantity
- cartItem model

**Listeners:**
- Track analytics
- Update recommendations
- Trigger marketing emails

### CartItemUpdated Event
**Triggered:** When quantity changed

**Data:**
- cartItem (with old and new quantity)
- oldQuantity
- newQuantity

### CartItemRemoved Event
**Triggered:** When item removed

**Data:**
- cartItem (before deletion)
- userId

### CartCleared Event
**Triggered:** When entire cart cleared

**Data:**
- userId
- itemCount (number of items removed)

**Listeners:**
- `ClearCartAfterOrder` - Automatic cleanup after order

## Business Logic

### Price Locking
When items are added to cart, the **current product price** is stored in the cart_item. This ensures:
- Price changes don't affect items already in cart
- Users see consistent pricing through checkout
- Admin price updates don't surprise customers mid-checkout

### Stock Validation
Before any cart operation:
1. Check `inventory.quantity_available`
2. Compare with requested quantity
3. Block operation if insufficient stock
4. Show available quantity to user

### Cart Persistence
- Cart tied to `user_id` (not session)
- Cart persists across:
  - Browser sessions
  - Device changes
  - Login/logout
  - Multiple days

### Duplicate Prevention
- Unique index on (user_id, product_id)
- Adding existing item: **increments quantity**
- Not adds duplicate row

## Security & Validation

### Authorization
- Users can only access their own cart
- Cart queries always filtered by `user_id`
- Policy checks enforce ownership

### Validation Rules
- Product must exist and be active
- Quantity between 1-100
- Stock availability verified
- Prices validated against database

### CSRF Protection
- All POST/PUT/DELETE requests require CSRF token
- AJAX requests include token in headers
- Laravel automatically validates

## Performance Optimization

### Database Queries
- Eager loading: `cart_items.product.images`
- Single query for cart totals
- Indexed lookups on user_id

### Caching (Optional Enhancement)
```php
// Cache user's cart for 5 minutes
$cartItems = Cache::remember("user_cart_{$userId}", 300, function () use ($userId) {
    return CartItem::where('user_id', $userId)->with('product')->get();
});
```

## Testing

### Feature Tests
```bash
# Run cart tests
php artisan test tests/Feature/Cart
```

**Test Coverage:**
- Add item to cart
- Update quantity
- Remove item
- Clear cart
- Stock validation
- Price locking
- Authorization checks
- AJAX response format

## Integration with Other Modules

### Dependencies:
- **Auth Module** - Requires authenticated user
- **Product Module** - Product data and images
- **Inventory Module** - Stock availability checks

### Used By:
- **Order Module** - Creates order from cart items
- **Checkout Process** - Displays cart for review

## Common Issues & Solutions

### Issue: "Duplicate items in cart"
**Cause:** Unique constraint not working
**Solution:** Check migration has unique index on (user_id, product_id)

### Issue: "Price mismatch at checkout"
**Cause:** Product price changed after adding to cart
**Solution:** Price locked in cart_items.price field at add time

### Issue: "Out of stock but still in cart"
**Cause:** Stock reduced after adding
**Solution:** Validate stock at checkout, show warning, block order

### Issue: "Cart count not updating"
**Cause:** AJAX call failing or response not handled
**Solution:** Check browser console, verify CSRF token, confirm route

## Future Enhancements
- Guest cart (session-based before login)
- Save for later functionality
- Wishlist integration
- Cart sharing via link
- Cart expiration (auto-clear after X days)
- Cart abandonment emails
- Product recommendations in cart
- Gift wrapping options
- Coupon/promo code application

---

**Module Version:** 1.0  
**Last Updated:** February 2026  
**Maintained By:** Development Team
