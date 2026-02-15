# Admin Module Documentation

## Overview
The Admin Module provides a comprehensive administrative panel for managing the entire e-commerce platform. It enables administrators to manage products, categories, orders, customers, and site settings through a secure, role-based interface.

## Purpose
- Provide centralized administrative control over the e-commerce platform
- Manage products, categories, and inventory
- Monitor and process customer orders
- Manage customer accounts and data
- Configure site-wide settings (name, footer, currency, social links, etc.)
- Generate reports and analytics

## Module Structure

```
Admin/
├── Controllers/
│   ├── AdminController.php           # Base admin controller
│   ├── DashboardController.php       # Admin dashboard & analytics
│   ├── CategoryController.php        # Category CRUD operations
│   ├── ProductController.php         # Product CRUD & image management
│   ├── OrderController.php           # Order management & status updates
│   ├── CustomerController.php        # Customer management
│   └── SettingsController.php        # Site settings management
├── Policies/
│   └── AdminPolicy.php               # Authorization logic
├── Requests/
│   ├── CategoryRequest.php           # Category validation
│   ├── ProductRequest.php            # Product validation
│   └── UpdateSettingsRequest.php     # Settings validation
├── Services/
│   └── AdminService.php              # Business logic layer
└── Routes.php                        # Admin route definitions
```

## Routes

All routes are prefixed with `/admin` and protected by `auth` and `admin` middleware.

### Dashboard
- **GET** `/admin` → `admin.dashboard` - Admin dashboard with statistics
- **GET** `/admin/dashboard` → Admin dashboard (alias)

### Category Management
- **GET** `/admin/categories` → `admin.categories.index` - List all categories
- **GET** `/admin/categories/create` → `admin.categories.create` - Create category form
- **POST** `/admin/categories` → `admin.categories.store` - Store new category
- **GET** `/admin/categories/{id}/edit` → `admin.categories.edit` - Edit category form
- **PUT** `/admin/categories/{id}` → `admin.categories.update` - Update category
- **DELETE** `/admin/categories/{id}` → `admin.categories.destroy` - Delete category

### Product Management
- **GET** `/admin/products` → `admin.products.index` - List all products
- **GET** `/admin/products/create` → `admin.products.create` - Create product form
- **POST** `/admin/products` → `admin.products.store` - Store new product
- **GET** `/admin/products/{id}/edit` → `admin.products.edit` - Edit product form
- **PUT** `/admin/products/{id}` → `admin.products.update` - Update product
- **DELETE** `/admin/products/{id}` → `admin.products.destroy` - Delete product
- **POST** `/admin/products/{id}/images` → `admin.products.images.upload` - Upload product image
- **DELETE** `/admin/products/{productId}/images/{imageId}` → `admin.products.images.delete` - Delete product image

### Order Management
- **GET** `/admin/orders` → `admin.orders.index` - List all orders
- **GET** `/admin/orders/{id}` → `admin.orders.show` - View order details
- **PUT** `/admin/orders/{id}/status` → `admin.orders.update-status` - Update order status
- **POST** `/admin/orders/{id}/cancel` → `admin.orders.cancel` - Cancel order

### Customer Management
- **GET** `/admin/customers` → `admin.customers.index` - List all customers
- **GET** `/admin/customers/{id}` → `admin.customers.show` - View customer details

### Site Settings
- **GET** `/admin/settings` → `admin.settings.index` - View/edit site settings form
- **PUT** `/admin/settings` → `admin.settings.update` - Update site settings

## Database Tables

### Primary Tables Managed:
- **users** - Customer accounts (read-only access for admins)
- **categories** - Product categories (managed via CategoryController)
- **products** - Product catalog (managed via ProductController)
- **product_images** - Product gallery images
- **orders** - Customer orders (status management)
- **order_items** - Items within orders
- **site_settings** - Global site configuration

### Relationships:
- Categories have hierarchical parent-child relationships
- Products belong to categories
- Orders contain multiple order items
- Products can have multiple images
- Site settings use key-value storage with caching

## Features & Functionality

### 1. Dashboard Analytics
- Total sales statistics
- Recent orders overview
- Low stock alerts
- Customer metrics
- Revenue charts

### 2. Product Management
- Full CRUD operations for products
- Multiple category assignment
- Pricing and sale price management
- Stock quantity tracking
- SEO meta data (title, description, keywords)
- Multiple image uploads with primary image selection
- Image gallery management

### 3. Category Management
- Create hierarchical category structure
- Set category visibility (active/inactive)
- Sort order management
- SEO-friendly slugs
- Parent-child category relationships

### 4. Order Processing
- View all customer orders with filtering
- Order status management:
  - Pending
  - Confirmed
  - Processing
  - Shipped
  - Delivered
  - Cancelled
- Order details with customer information
- Order item breakdown
- Cancel orders with inventory restoration

### 5. Customer Management
- View customer list with search and filters
- Customer details (profile, orders, addresses)
- Customer order history
- Account status monitoring

### 6. Site Settings Management
- General settings (site name, tagline)
- Currency configuration (6 currencies available: INR, USD, EUR, GBP, AUD, CAD)
- Footer settings (email, phone, address, about text)
- Social media links (Facebook, Instagram, Twitter)
- Quick links content (About Us, Contact, FAQs, Return Policy)
- Settings cached for performance (1 hour TTL)

## Output

### Admin Dashboard
- Displays comprehensive statistics and metrics
- Quick access links to all admin sections
- Recent activity feed
- Low stock alerts
- Revenue charts and graphs

### Product Management Interface
- Paginated product list with search and filters
- Product form with:
  - Basic info (name, slug, SKU, description)
  - Pricing (regular price, sale price, cost price)
  - Stock tracking
  - Category selection
  - SEO fields
  - Image upload with drag-and-drop
  - Image gallery management

### Order Management Interface
- Filterable order list (by status, date, customer)
- Order detail view with:
  - Customer information
  - Shipping address
  - Order items with images
  - Payment details
  - Status history
  - Action buttons (confirm, ship, deliver, cancel)

### Settings Page
- Organized sections:
  - General Settings
  - Currency Settings (dropdown with symbol preview)
  - Social Media Links (with platform icons)
  - Quick Links Content (textareas for page content)
  - Footer Settings
- Form validation with error messages
- Success notifications

## How to Use

### Access the Admin Panel

1. **Login as Admin:**
   ```
   Navigate to: /login
   Use credentials with is_admin = true
   ```

2. **Access Dashboard:**
   ```
   After login, navigate to: /admin
   Or click "Admin Panel" in user dropdown menu
   ```

### Managing Products

1. **Create New Product:**
   - Go to `/admin/products/create`
   - Fill in required fields (name, price, SKU, description)
   - Select category
   - Set stock quantity
   - Upload product images
   - Click "Create Product"

2. **Edit Product:**
   - Go to `/admin/products`
   - Click "Edit" on desired product
   - Update fields
   - Upload/delete images
   - Click "Update Product"

3. **Delete Product:**
   - Go to `/admin/products`
   - Click "Delete" on desired product
   - Confirm deletion (soft delete)

### Managing Categories

1. **Create Category:**
   - Go to `/admin/categories/create`
   - Enter name (slug auto-generated)
   - Select parent category (optional)
   - Set active status
   - Set sort order
   - Click "Create Category"

2. **Edit/Delete Categories:**
   - Similar workflow to products

### Processing Orders

1. **View Orders:**
   - Go to `/admin/orders`
   - Use filters to find specific orders
   - Click order number to view details

2. **Update Order Status:**
   - Open order details
   - Use status action buttons:
     - "Confirm Order" - Confirms payment
     - "Mark as Processing" - Order being prepared
     - "Mark as Shipped" - Order dispatched
     - "Mark as Delivered" - Order completed
   - Status updates trigger email notifications

3. **Cancel Order:**
   - Open order details
   - Click "Cancel Order"
   - Inventory automatically restored
   - Refund process initiated

### Managing Site Settings

1. **Configure General Settings:**
   - Go to `/admin/settings`
   - Update website name and tagline
   - Changes reflect immediately across site

2. **Set Currency:**
   - Select from currency dropdown (INR, USD, EUR, GBP, AUD, CAD)
   - Format shows: "CODE - SYMBOL (Name)"
   - Default: INR - ₹ (Indian Rupee)

3. **Configure Social Media:**
   - Enter full URLs for social platforms
   - Leave blank to hide icons
   - Icons appear in footer automatically

4. **Manage Quick Links Content:**
   - Write content for static pages
   - Use textareas for multi-paragraph content
   - Content displayed when users click footer links

5. **Update Footer:**
   - Set contact email, phone, address
   - Write brief "About" text
   - All fields optional

### Customer Management

1. **View Customers:**
   - Go to `/admin/customers`
   - Search by name or email
   - Click customer to view full profile

2. **Customer Details:**
   - View order history
   - See saved addresses
   - Monitor account activity

## Permissions & Security

### Middleware Protection
- All routes protected by `auth` middleware (must be logged in)
- All routes protected by `admin` middleware (must have is_admin = true)
- Unauthorized access redirects to home page

### Policy Authorization
- Admin policies enforce permission checks
- Controllers use `$this->authorize()` for fine-grained control
- Prevents privilege escalation

### Validation
- All form submissions validated via FormRequest classes
- Server-side validation prevents invalid data
- Custom error messages for user-friendly feedback

## Dependencies

### Internal Dependencies
- Product Module (for product management)
- Order Module (for order processing)
- User Module (for customer management)
- Inventory Module (for stock tracking)
- SiteSetting Model (for configuration)

### External Dependencies
- Laravel Authorization (Policies & Gates)
- Laravel Validation (FormRequests)
- Laravel Storage (for image uploads)
- Laravel Cache (for settings performance)

## Events & Listeners

### Events Triggered
- `OrderStatusUpdated` - When order status changes
- `ProductCreated` - When new product added
- `ProductUpdated` - When product modified
- `ProductDeleted` - When product removed

### Use Cases
- Send email notifications on order status changes
- Update inventory when products change
- Log administrative actions
- Sync with external systems

## Configuration

### Admin User Setup
To create an admin user:

```bash
php artisan tinker
$user = User::find(1);  // or create new user
$user->is_admin = true;
$user->save();
```

Or use the AdminUserSeeder:
```bash
php artisan db:seed --class=AdminUserSeeder
```

### Settings Cache
Settings are cached for performance:
- Cache duration: 3600 seconds (1 hour)
- Auto-cleared on update
- Manual clear: `SiteSetting::clearCache()`

## Testing

### Feature Tests
Located in `tests/Feature/Admin/`:
- Dashboard access tests
- Product CRUD tests
- Category management tests
- Order processing tests
- Settings update tests

### Usage:
```bash
php artisan test --filter AdminTest
php artisan test tests/Feature/Admin
```

## Common Tasks

### Add New Setting
1. Add migration to site_settings table
2. Run migration
3. Add field to settings form view
4. Update UpdateSettingsRequest validation
5. Update SettingsController to handle new field

### Customize Dashboard
1. Edit `DashboardController@index`
2. Add queries for new metrics
3. Update `views/pages/admin/dashboard.blade.php`

### Add Admin Navigation Item
1. Edit `views/layouts/admin.blade.php`
2. Add link in sidebar navigation
3. Set active state for current route

## Future Enhancements
- Bulk product operations
- Advanced reporting and analytics
- Export orders to CSV/Excel
- Email marketing campaigns
- Coupon/discount management
- Review and rating moderation
- Inventory forecasting
- Multi-language support

---

**Module Version:** 1.0  
**Last Updated:** February 2026  
**Maintained By:** Development Team
