# Vendor Module Documentation

## Overview

The Vendor module implements a production-grade multi-vendor marketplace system. This module allows sellers to apply for vendor accounts, enables admin approval workflows, and provides vendor dashboards for product management.

## Features

- **Vendor Application System**: Public users can apply to become vendors
- **Admin Approval Workflow**: Applications are reviewed and approved/rejected by admins
- **Role-Based Access Control**: Separate permissions for customers, vendors, and admins
- **Vendor Dashboard**: Dedicated interface for vendors to manage their products
- **Vendor Storefronts**: Public-facing pages for each approved vendor
- **Product Ownership**: All products are linked to vendors
- **Commission Management**: Configurable commission rates for each vendor

## Architecture

The Vendor module follows Laravel's best practices and the existing application architecture:

```
app/Modules/Vendor/
├── Controllers/          # Request handling
│   ├── VendorApplicationController.php
│   ├── VendorDashboardController.php
│   └── VendorProductController.php
├── Models/              # Data models
│   ├── Vendor.php
│   └── VendorApplication.php
├── Repositories/        # Data access layer
│   ├── Contracts/
│   │   └── VendorRepositoryInterface.php
│   └── VendorRepository.php
├── Services/            # Business logic
│   └── VendorService.php
├── DTOs/               # Data transfer objects
│   └── VendorDTO.php
├── Requests/           # Form request validation
│   ├── SubmitVendorApplicationRequest.php
│   ├── RegisterVendorRequest.php
│   ├── UpdateVendorRequest.php
│   ├── ApproveVendorRequest.php
│   └── RejectVendorRequest.php
├── Policies/           # Authorization
│   └── VendorPolicy.php
├── Events/             # Application events
│   ├── VendorApplicationSubmitted.php
│   ├── VendorRegistered.php
│   ├── VendorApproved.php
│   ├── VendorRejected.php
│   └── VendorSuspended.php
├── Listeners/          # Event handlers
│   ├── SendVendorRegistrationNotification.php
│   ├── SendVendorApprovalNotification.php
│   ├── SendVendorRejectionNotification.php
│   └── SendVendorSuspensionNotification.php
└── Routes.php          # Module routes
```

## Database Schema

### vendors table
- Stores approved vendor profiles
- Links to users table (vendor user accounts) via `user_id`
- Contains business information, banking details, and status
- Uses soft deletes (can restore if reapplying with same business name)

### vendor_applications table
- **CRITICAL**: Links to `users` table via `user_id` foreign key (not nullable)
- Stores pending/processed vendor applications
- Contains business information before approval
- Links to vendors table after approval via `vendor_id`
- Business email field is optional (defaults to user's email)
- Status: 'pending', 'approved', 'rejected'

### users table (modified)
- Added `role` field: 'customer', 'vendor', 'admin'
- Maintains backward compatibility with existing `is_admin` field
- One-to-one relationship with `vendor_applications` (hasOne)
- One-to-one relationship with `vendors` (hasOne)

### products table (modified)
- Added `vendor_id` foreign key (nullable for admin products)
- Links each product to its vendor
- Vendors can only access products where `vendor_id` matches their ID

## User Flow

### Vendor Application Flow (Profile-Based)

⚠️ **IMPORTANT**: Vendor applications are now processed through authenticated user profiles, not public registration.

1. **User registers normally**: Standard registration at `/register` (no vendor-specific fields)
2. **User logs in**: Uses their regular credentials
3. **User navigates to profile**: At `/profile`, sees "Convert to Business Account" button
4. **User applies for vendor account**: Fills out vendor application form at `/vendor/apply` (authenticated route)
   - Application is automatically linked to their `user_id`
   - Business email is optional (defaults to user's email if not provided)
   - Status shown directly in user profile
5. **Application stored**: Creates `VendorApplication` record with status 'pending' and links to user
6. **User sees pending status**: Profile page shows application details and "Pending Review" alert
7. **Admin reviews**: Views application at `/admin/vendors/applications`
8. **Admin approves**:
   - Updates existing user's role to 'vendor' (no duplicate user creation)
   - Creates `Vendor` profile
   - Handles duplicate business names by adding numeric suffixes
   - Restores soft-deleted vendors if business name exists
   - Links application to vendor
9. **User sees approval**: Profile page shows congratulations card with "Go to Vendor Dashboard" button
10. **Vendor accesses dashboard**: At `/vendor/dashboard`

**If rejected**: User sees rejection reason in profile with "Apply Again" button to reapply.

### Vendor Product Management

1. Vendor logs in with their credentials
2. Accesses vendor dashboard at `/vendor/dashboard`
3. Views vendor statistics:
   - Total revenue (calculated from `order_items.subtotal`)
   - Total orders
   - Total products
   - Pending orders count
4. Manages products at `/vendor/products`:
   - Views **only their own products** (filtered by `vendor_id`)
   - Can see products in all statuses (active, inactive, out_of_stock)
   - Can create new products
   - Can edit only their own products (403 if not owner)
   - Can delete only their own products (403 if not owner)
5. Cannot access other vendors' products or admin features

## Key Components

### VendorService
Handles all vendor business logic:
- Vendor registration and updates
- Application approval/rejection workflow
- Vendor statistics and analytics
- File uploads (logo, banner, documents)

### VendorRepository
Data access layer for vendors:
- CRUD operations
- Filtering and pagination
- Status management
- Statistics queries (revenue, orders, products count)
- **Critical Fix**: Uses `order_items.subtotal` for revenue calculation

### ProductRepository (Modified)
- **Added vendor_id filtering**: `getPaginated()` method filters products by vendor
- **Status filter logic**: Defaults to active only for public views, shows all statuses for vendor views
- Ensures vendor product isolation at data access layer

### VendorApplicationController
- **Requires authentication**: All application routes protected with auth middleware
- Auto-links applications to authenticated user via `user_id`
- Business email defaults to user's email if not provided
- Handles reapplication for rejected applications

### VendorProductController
- **index()**: Lists vendor's products filtered by `vendor_id`
- **edit()**: Verifies product ownership before showing edit form (abort 403 if not owner)
- **destroy()**: Deletes product after ownership verification (abort 403 if not owner)
- All actions ensure vendors can only manage their own products

### VendorManagementController (Admin)
- **approveApplication()**: 
  - Checks for existing user via `user_id` (prevents duplicate user creation)
  - Updates existing user's role to 'vendor'
  - Generates unique business names/slugs with numeric suffixes
  - Restores soft-deleted vendors if business name matches
  - Creates vendor profile and links application

### Middleware
- `IsVendor`: Ensures user has vendor role and approved vendor account
- `IsAdmin`: Ensures user has admin privileges (existing)
- Applied to respective route groups for access control

### Policies
- `VendorPolicy`: Authorization rules for vendor actions
- Ensures vendors can only manage their own resources

## Critical Files Modified

### Backend Files
1. **app/Modules/Vendor/Controllers/VendorApplicationController.php**
   - Added auth requirement
   - Auto-links to user_id
   - Email field optional with user email default

2. **app/Modules/Admin/Controllers/VendorManagementController.php**
   - Fixed duplicate user creation
   - Added unique business name/slug generation
   - Handles soft-deleted vendor restoration

3. **app/Modules/Product/Repositories/ProductRepository.php**
   - Added vendor_id filtering (lines 36-40)
   - Modified status filter logic (lines 51-56)

4. **app/Modules/Vendor/Controllers/VendorProductController.php**
   - Added destroy() method with ownership verification
   - All methods verify product ownership

5. **app/Modules/Vendor/Repositories/VendorRepository.php**
   - Fixed getVendorStats() to use order_items.subtotal

6. **app/Modules/Vendor/Routes.php**
   - Protected application routes with auth middleware
   - Added DELETE route for product deletion

### Frontend Files
1. **resources/views/pages/vendor/application-form.blade.php**
   - Made email field optional
   - Added note that it defaults to user's email

2. **resources/views/pages/user/profile.blade.php**
   - Added three-state Business Account card:
     * Approved: Green success with vendor dashboard access
     * Pending: Yellow alert with application details
     * Rejected: Red alert with rejection reason and reapply button
     * No application: Blue card with apply button

3. **resources/views/pages/vendor/products/index.blade.php**
   - Fixed ProductDTO property names (camelCase)
   - Added delete button with confirmation dialog

4. **resources/views/pages/vendor/dashboard.blade.php**
   - Displays correct revenue using subtotal calculation

### Database Files
1. **database/migrations/[timestamp]_create_vendor_applications_table.php**
   - Added user_id foreign key (not nullable)
   - Links applications to users table

2. **database/migrations/[timestamp]_create_products_table.php**
   - vendor_id foreign key for product ownership

## Routes

### Public Routes
- `GET /vendor/store/{slug}` - Public vendor storefront

### Authenticated User Routes (Auth Required)
⚠️ **Changed**: Vendor application is now only accessible to authenticated users
- `GET /vendor/apply` - Vendor application form (requires authentication)
- `POST /vendor/apply` - Submit vendor application (requires authentication)

### Vendor Routes (Auth + Vendor Role Required)
- `GET /vendor/dashboard` - Vendor dashboard with statistics
- `GET /vendor/profile` - Vendor profile
- `GET /vendor/products` - List vendor's products (filtered by vendor_id)
- `GET /vendor/products/create` - Create new product form
- `POST /vendor/products` - Store new product
- `GET /vendor/products/{id}/edit` - Edit product form (ownership verified)
- `PUT/PATCH /vendor/products/{id}` - Update product (ownership verified)
- `DELETE /vendor/products/{id}` - Delete product (ownership verified)

**Vendor Order Management Routes** (NEW - Order Splitting Feature):
- `GET /vendor/orders` - List all vendor orders (only vendor's orders shown)
- `GET /vendor/orders/{id}` - View specific vendor order details
- `POST /vendor/orders/{id}/accept` - Accept order (change status to accepted)
- `POST /vendor/orders/{id}/pack` - Mark order as packed
- `POST /vendor/orders/{id}/ship` - Ship order with tracking details
- `POST /vendor/orders/{id}/deliver` - Mark order as delivered
- `POST /vendor/orders/{id}/cancel` - Cancel order with reason
- `POST /vendor/orders/{id}/reject` - Reject pending order with reason

### Admin Routes (Auth + Admin Role Required)
- `GET /admin/vendors/applications` - View all vendor applications
- `GET /admin/vendors/applications/{id}` - Application details
- `POST /admin/vendors/applications/{id}/approve` - Approve application
- `POST /admin/vendors/applications/{id}/reject` - Reject application
- `GET /admin/vendors` - List all vendors
- `GET /admin/vendors/{id}` - Vendor details
- `POST /admin/vendors/{id}/suspend` - Suspend vendor
- `POST /admin/vendors/{id}/reactivate` - Reactivate vendor

**Admin Role in Marketplace**: Admin is now a marketplace manager, NOT a shopkeeper:
- Monitors all vendor orders across the platform
- Handles customer disputes and vendor issues
- Can suspend misbehaving vendors
- Views platform-wide analytics and commission
- Does NOT process shipments (vendors do that)
- Does NOT pack orders (vendors do that)
- Focuses on platform operations, fraud detection, and vendor management

## Events & Listeners

### Events
- `VendorApplicationSubmitted`: Fired when a new application is submitted
- `VendorApproved`: Fired when admin approves an application
- `VendorRejected`: Fired when admin rejects an application
- `VendorSuspended`: Fired when admin suspends a vendor

### Listeners
All listeners implement `ShouldQueue` for asynchronous processing:
- Send email notifications to applicants
- Send notifications to admins for review
- Log important vendor actions

## Security Considerations

1. **Role-Based Access Control**: Strict separation between customer, vendor, and admin roles
2. **Resource Ownership**: Vendors can only access their own resources
   - Product listing filtered by `vendor_id` at repository level
   - Edit/Delete operations verify ownership (abort 403 if not owner)
3. **Application Validation**: Comprehensive validation for all application data
   - Business email is optional and defaults to authenticated user's email
4. **Authentication Required**: Vendor applications require authenticated user accounts
   - Applications linked to `user_id` (cannot apply without account)
   - Status displayed directly in user profile
5. **Admin Approval Required**: Vendors cannot self-activate accounts
6. **Duplicate Prevention**:
   - Checks for existing user via `user_id` (prevents duplicate user creation)
   - Auto-generates unique business names/slugs with numeric suffixes
   - Restores soft-deleted vendors if reapplying with same business
7. **Suspension Capability**: Admins can suspend misbehaving vendors
8. **File Upload Security**: Validated file types and sizes for uploads
9. **Product Isolation**: Repository-level filtering ensures vendors only see their own products
10. **Status Filtering**: Vendors see products in all statuses; public only sees active products

## Recent Bug Fixes & Improvements

### Phase 1: Profile-Based Application Flow (Major Restructuring)
✅ **Changed vendor application from public to authenticated-user-only**
- Removed vendor signup options from login/register pages
- Added "Convert to Business Account" button in user profile
- Applications now linked to `user_id` (foreign key in `vendor_applications`)
- Updated `VendorApplicationController` to require authentication
- Modified routes to protect vendor application endpoints with auth middleware

✅ **Updated Models with Relationships**
- `User` model: Added `hasOne` relationships to `VendorApplication` and `Vendor`
- `VendorApplication` model: Added `belongsTo` relationship to `User`

### Phase 2: Email & Validation Fixes
✅ **Fixed email field handling**
- Made business email optional in vendor application form
- Defaults to authenticated user's email if not provided
- Updated `SubmitVendorApplicationRequest` validation rules

✅ **Fixed route name errors**
- Corrected `route('profile')` to `route('profile.show')` throughout application

### Phase 3: Approval Logic Improvements
✅ **Fixed duplicate user creation on approval**
- Admin approval now checks for existing user via `user_id`
- Updates existing user's role to 'vendor' instead of creating duplicate
- Prevents "email already exists" errors

✅ **Fixed duplicate business name/slug handling**
- Automatically generates unique business names with numeric suffixes (e.g., "Business Name 2")
- Checks for soft-deleted vendors and restores them if reapplying
- Generates unique slugs to prevent conflicts

### Phase 4: Profile Page Enhancement
✅ **Added vendor status display in user profile**
- **Approved**: Green success card with congratulations message and "Go to Vendor Dashboard" button
- **Pending**: Yellow alert showing application details and "Pending Review" status
- **Rejected**: Red alert showing rejection reason with "Apply Again" button
- **No Application**: Blue card with "Convert to Business Account" button

### Phase 5: Vendor Dashboard Fixes
✅ **Fixed revenue calculation error**
- Changed from `order_items.total` to `order_items.subtotal` in `VendorRepository::getVendorStats()`
- Dashboard now correctly displays vendor revenue

### Phase 6: Product Management Fixes
✅ **Fixed ProductDTO property mismatches**
- Updated vendor products view to use camelCase properties:
  - `stock_quantity` → `stockQuantity`
  - `primary_image_url` → `primaryImageUrl`
  - `created_at` → `createdAt` (with proper date parsing)

✅ **Implemented vendor product isolation** (Critical Security Fix)
- Added `vendor_id` filter in `ProductRepository::getPaginated()`
- Vendors now only see their own products in the product list
- Modified status filter: vendors see all statuses, public only sees active

✅ **Added delete functionality for vendor products**
- Implemented `destroy()` method in `VendorProductController`
- Added ownership verification (abort 403 if vendor doesn't own product)
- Added DELETE route: `vendor.products.destroy`
- Restored delete button in products list with confirmation dialog

### Phase 7: Order Splitting System (MARKETPLACE TRANSFORMATION)
✅ **Implemented Real Multi-Vendor Marketplace with Order Splitting**

#### Database Layer
- **Created `vendor_orders` table**: Sits between orders and order_items
- **Added vendor_order_id to order_items**: Links items to vendor-specific orders
- **VendorOrderStatus enum**: pending → accepted → packed → shipped → delivered (or cancelled/rejected)

#### Core Business Logic
- **VendorOrderService**: Handles order splitting and vendor order lifecycle
  - `splitOrderIntoVendorOrders()`: Automatically splits customer orders by vendor
  - Groups order items by product's vendor_id
  - Creates separate vendor_order for each vendor
  - Calculates commission and vendor earnings per order
  - Generates unique vendor order numbers (format: VND-{VENDOR}-{DATE}-{SEQ})

#### Order Flow
1. **Customer places order** → One main order created
2. **System automatically splits order** → Creates multiple vendor_orders (one per vendor)
3. **Each vendor sees only their order** → Filtered by vendor_id
4. **Vendor manages their order**:
   - Accept → Pack → Ship → Deliver
   - Or Cancel/Reject with reason
5. **Customer sees unified order** → But fulfillment is handled by individual vendors

#### Vendor Order Management
- **VendorOrderController**: Complete CRUD for vendor orders
  - View all orders (paginated, filterable by status)
  - View order details (items, customer info, shipping address)
  - Accept order (vendor confirms they can fulfill)
  - Pack Order (marks items as ready)
  - Ship order (with tracking number and carrier)
  - Deliver order (marks as completed)
  - Cancel/Reject order (with mandatory reason)

#### Commission & Earnings
- Each vendor_order tracks:
  - Subtotal (sum of vendor's items)
  - Commission rate (from vendor profile)
  - Commission amount (calculated)
  - Vendor earnings (subtotal - commission)

#### Security & Isolation
- Vendors only see/manage their own vendor_orders
- Ownership verified on every action (abort 403 if unauthorized)
- Order items automatically linked to correct vendor_order
- Customer address shared with vendors for shipping

#### Why This Matters
**Before**: Single-shop model where admin processes all orders
**After**: True marketplace where vendors independently fulfill their own orders

This transforms the system from:
- ❌ Multi-seller catalog (like OLX - just listings)
- ✅ Operational marketplace (like Amazon - real fulfillment)

### Current System Capabilities  
✅ **Complete End-to-End Marketplace Flow Working**
1. User registers normally → logs in
2. User clicks "Convert to Business Account" in profile
3. User fills vendor application (auto-linked to user_id)
4. Admin reviews and approves application
5. Vendor adds products to their store
6. Customer adds items from multiple vendors to cart
7. **Customer places ONE order**
8. **System automatically splits into VENDOR ORDERS**
9. **Each vendor independently fulfills their part**:
   - Accepts order
   - Packs items
   - Ships with tracking
   - Marks delivered
10. Customer receives items from different vendors independently

✅ **Security Features Active**
- Repository-level filtering by vendor_id for products
- Repository-level filtering by vendor_id for orders
- Controller-level ownership verification for all actions
- Route protection with auth + vendor middleware
- Prevention of duplicate users and business names
- Soft delete restoration for reapplications
- Commission tracking and earnings calculations

## Future Enhancements

- [ ] Email notifications for all events (application submitted, approved, rejected)
- [ ] Product creation/update forms for vendors (routes exist, forms needed)
- [ ] Product image management interface for vendors
- [ ] Vendor earnings and payout tracking
- [ ] Vendor analytics and reporting (sales charts, trends)
- [ ] Document verification for KYC compliance
- [ ] Vendor subscription tiers with different feature sets
- [ ] Review and rating system for vendors
- [ ] Vendor messaging system (customer-to-vendor communication)
- [ ] Bulk product upload for vendors (CSV/Excel import)
- [ ] Vendor API for third-party integrations
- [ ] Vendor notification preferences
- [ ] Multi-language support for vendor dashboards
- [ ] Product inventory alerts for low stock
- [ ] Vendor commission history and reports

## Testing

To create test data:

```php
// Create a vendor with approved status
$vendor = Vendor::factory()->create();

// Create a pending vendor application
$application = VendorApplication::factory()->create();

// Create a rejected application
$rejectedApp = VendorApplication::factory()->rejected()->create();
```

## Dependencies

- Laravel 12
- Existing User, Product, and Category modules
- Storage disk configuration for file uploads
- Queue configuration for async notifications
