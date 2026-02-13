# Product Module Documentation

**Version:** 1.0.0  
**Phase:** 4 - Product & Category Module  
**Created:** February 10, 2026  
**Laravel Version:** 12.x  
**PHP Version:** 8.2.12

---

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Database Schema](#database-schema)
4. [Models & Relationships](#models--relationships)
5. [Repository Pattern](#repository-pattern)
6. [Service Layer](#service-layer)
7. [DTOs (Data Transfer Objects)](#dtos-data-transfer-objects)
8. [Request Validation](#request-validation)
9. [Controllers](#controllers)
10. [Policies & Authorization](#policies--authorization)
11. [Routes](#routes)
12. [Usage Examples](#usage-examples)
13. [API Reference](#api-reference)
14. [Testing](#testing)

---

## Overview

The Product Module is the core catalog system for the e-commerce platform. It handles all product and category management functionality, including:

- **Category Management** - Hierarchical category system with parent-child relationships
- **Product Management** - Complete CRUD operations for products
- **Product Images** - Multiple image support per product with primary image designation
- **Search & Filtering** - Advanced product search with multiple filters
- **Stock Management** - Inventory tracking and status management
- **Pricing** - Regular and sale pricing with automatic discount calculations

### Module Structure

```
app/Modules/Product/
├── Controllers/
│   └── ProductController.php          # Customer-facing product browsing
├── DTOs/
│   ├── CategoryDTO.php                # Category data transfer object
│   └── ProductDTO.php                 # Product data transfer object
├── Models/
│   ├── Category.php                   # Category Eloquent model
│   ├── Product.php                    # Product Eloquent model
│   └── ProductImage.php               # Product image Eloquent model
├── Policies/
│   ├── CategoryPolicy.php             # Category authorization
│   └── ProductPolicy.php              # Product authorization
├── Repositories/
│   ├── Contracts/
│   │   ├── CategoryRepositoryInterface.php
│   │   ├── ProductRepositoryInterface.php
│   │   └── ProductImageRepositoryInterface.php
│   ├── CategoryRepository.php         # Category data access
│   ├── ProductRepository.php          # Product data access
│   └── ProductImageRepository.php     # Product image data access
├── Requests/
│   ├── ProductImageRequest.php        # Image upload validation
│   ├── StoreCategoryRequest.php       # Category creation validation
│   ├── StoreProductRequest.php        # Product creation validation
│   ├── UpdateCategoryRequest.php      # Category update validation
│   └── UpdateProductRequest.php       # Product update validation
├── Services/
│   ├── CategoryService.php            # Category business logic
│   └── ProductService.php             # Product business logic
├── Routes.php                          # Product module routes
└── Documentation.md                    # This file
```

---

## Architecture

This module follows a clean, layered architecture pattern:

### Layered Architecture

```
┌──────────────────────────────────────┐
│         Controllers Layer            │  ← HTTP Request/Response
│  (ProductController, Admin)          │
└──────────────┬───────────────────────┘
               │
┌──────────────▼───────────────────────┐
│      Request Validation Layer        │  ← Validation Rules
│  (Form Requests)                     │
└──────────────┬───────────────────────┘
               │
┌──────────────▼───────────────────────┐
│         Service Layer                │  ← Business Logic
│  (CategoryService, ProductService)   │
└──────────────┬───────────────────────┘
               │
┌──────────────▼───────────────────────┐
│       Repository Layer               │  ← Data Access
│  (CategoryRepository, etc.)          │
└──────────────┬───────────────────────┘
               │
┌──────────────▼───────────────────────┐
│         Model Layer                  │  ← Database ORM
│  (Category, Product, ProductImage)   │
└──────────────────────────────────────┘
```

### Design Patterns Used

1. **Repository Pattern** - Abstracts data access logic
2. **Service Layer Pattern** - Encapsulates business logic
3. **DTO Pattern** - Type-safe data transfer between layers
4. **Factory Pattern** - Test data generation
5. **Policy Pattern** - Authorization logic separation

### SOLID Principles Applied

- **Single Responsibility** - Each class has one specific purpose
- **Open/Closed** - Extensible via interfaces without modification
- **Liskov Substitution** - Repository implementations are interchangeable
- **Interface Segregation** - Focused, specific interfaces
- **Dependency Inversion** - Depends on abstractions (interfaces), not concrete implementations

---

## Database Schema

### Categories Table

Stores all product categories with support for hierarchical structure.

```sql
categories
├── id (bigint, primary key, auto_increment)
├── name (string, 255)
├── slug (string, 255, unique, indexed)
├── description (text, nullable)
├── parent_id (bigint, nullable, foreign key → categories.id)
├── is_active (boolean, default: true, indexed)
├── sort_order (integer, default: 0)
├── created_at (timestamp)
└── updated_at (timestamp)

Indexes:
- PRIMARY KEY (id)
- UNIQUE (slug)
- INDEX (slug, is_active)
- INDEX (parent_id)
- FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
```

### Products Table

Stores all product information with soft delete support.

```sql
products
├── id (bigint, primary key, auto_increment)
├── category_id (bigint, foreign key → categories.id)
├── name (string, 255)
├── slug (string, 255, unique, indexed)
├── description (text, nullable)
├── short_description (text, nullable)
├── price (decimal 10,2)
├── sale_price (decimal 10,2, nullable)
├── stock_quantity (integer, default: 0)
├── sku (string, 100, unique, nullable)
├── status (enum: active, inactive, out_of_stock, default: active)
├── is_featured (boolean, default: false, indexed)
├── weight (decimal 8,2, nullable)
├── meta_data (json, nullable)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp, nullable) -- Soft Deletes

Indexes:
- PRIMARY KEY (id)
- UNIQUE (slug)
- UNIQUE (sku)
- INDEX (slug, status)
- INDEX (category_id)
- INDEX (is_featured)
- FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
```

### Product Images Table

Stores multiple images per product with primary image support.

```sql
product_images
├── id (bigint, primary key, auto_increment)
├── product_id (bigint, foreign key → products.id)
├── image_path (string, 255)
├── alt_text (string, 255, nullable)
├── is_primary (boolean, default: false, indexed)
├── sort_order (integer, default: 0)
├── created_at (timestamp)
└── updated_at (timestamp)

Indexes:
- PRIMARY KEY (id)
- INDEX (product_id)
- INDEX (product_id, is_primary)
- FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
```

### Relationships Diagram

```
categories (1) ──┬── (many) categories (children)
                 │
                 └── (many) products
                              │
                              └── (many) product_images
```

---

## Models & Relationships

### Category Model

**File:** `app/Modules/Product/Models/Category.php`

#### Fillable Attributes
- `name`, `slug`, `description`, `parent_id`, `is_active`, `sort_order`

#### Casts
- `is_active` → boolean
- `sort_order` → integer

#### Relationships

```php
// Parent category
public function parent(): BelongsTo
// Returns: Category|null

// Child categories (subcategories)
public function children(): HasMany
// Returns: Collection<Category>

// Products in this category
public function products(): HasMany
// Returns: Collection<Product>

// Only active products
public function activeProducts(): HasMany
// Returns: Collection<Product>
```

#### Query Scopes

```php
// Get only active categories
Category::active()->get();

// Get only root categories (no parent)
Category::root()->get();
```

#### Helper Methods

```php
// Check if category has child categories
$category->hasChildren(): bool

// Check if category is a root category
$category->isRoot(): bool
```

---

### Product Model

**File:** `app/Modules/Product/Models/Product.php`

#### Features
- Soft Deletes enabled
- Factory support for testing

#### Fillable Attributes
- `category_id`, `name`, `slug`, `description`, `short_description`
- `price`, `sale_price`, `stock_quantity`, `sku`, `status`
- `is_featured`, `weight`, `meta_data`

#### Casts
- `price` → decimal:2
- `sale_price` → decimal:2
- `weight` → decimal:2
- `stock_quantity` → integer
- `is_featured` → boolean
- `meta_data` → array

#### Relationships

```php
// Category this product belongs to
public function category(): BelongsTo
// Returns: Category

// All images for this product
public function images(): HasMany
// Returns: Collection<ProductImage>

// Primary/featured image
public function primaryImage(): HasOne
// Returns: ProductImage|null
```

#### Query Scopes

```php
// Get only active products
Product::active()->get();

// Get only featured products
Product::featured()->get();

// Get only in-stock products
Product::inStock()->get();

// Get products by category
Product::byCategory($categoryId)->get();
```

#### Business Logic Methods

```php
// Check if product is on sale
$product->isOnSale(): bool

// Get effective price (sale price if available, otherwise regular)
$product->getEffectivePrice(): float

// Check if product is in stock
$product->isInStock(): bool

// Calculate discount percentage
$product->getDiscountPercentage(): ?int
```

---

### ProductImage Model

**File:** `app/Modules/Product/Models/ProductImage.php`

#### Fillable Attributes
- `product_id`, `image_path`, `alt_text`, `is_primary`, `sort_order`

#### Casts
- `is_primary` → boolean
- `sort_order` → integer

#### Relationships

```php
// Product this image belongs to
public function product(): BelongsTo
// Returns: Product
```

#### Helper Methods

```php
// Get full URL of the image
$image->getImageUrl(): string
// Returns: Full URL like "http://example.com/storage/products/image.jpg"
```

---

## Repository Pattern

The Repository Pattern abstracts the data access layer, making the code more maintainable and testable.

### CategoryRepository

**Interface:** `CategoryRepositoryInterface`  
**Implementation:** `CategoryRepository`

#### Available Methods

```php
// Get all categories
all(): Collection

// Get all active categories
getAllActive(): Collection

// Get root categories (no parent)
getRootCategories(): Collection

// Find category by ID
findById(int $id): ?Category

// Find category by slug
findBySlug(string $slug): ?Category

// Get subcategories of a parent
getSubcategories(int $parentId): Collection

// Create new category
create(array $data): Category

// Update category
update(int $id, array $data): Category

// Delete category
delete(int $id): bool

// Check if category has products
hasProducts(int $id): bool
```

#### Usage Example

```php
use App\Modules\Product\Repositories\Contracts\CategoryRepositoryInterface;

class SomeService
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepo
    ) {}

    public function example()
    {
        // Get all active categories
        $categories = $this->categoryRepo->getAllActive();
        
        // Find by slug
        $category = $this->categoryRepo->findBySlug('electronics');
        
        // Create new
        $newCategory = $this->categoryRepo->create([
            'name' => 'New Category',
            'slug' => 'new-category',
            'is_active' => true,
        ]);
    }
}
```

---

### ProductRepository

**Interface:** `ProductRepositoryInterface`  
**Implementation:** `ProductRepository`

#### Available Methods

```php
// Get all products
all(): Collection

// Get paginated products with filters
getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator

// Find product by ID
findById(int $id): ?Product

// Find product by slug
findBySlug(string $slug): ?Product

// Get products by category (paginated)
getByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator

// Get featured products
getFeatured(int $limit = 8): Collection

// Get latest products
getLatest(int $limit = 8): Collection

// Create new product
create(array $data): Product

// Update product
update(int $id, array $data): Product

// Delete product
delete(int $id): bool

// Update stock quantity
updateStock(int $id, int $quantity): bool

// Search products by keyword
search(string $keyword, int $perPage = 15): LengthAwarePaginator
```

#### Advanced Filtering

The `getPaginated()` method supports these filters:

```php
$filters = [
    'category_id' => 1,              // Filter by category
    'status' => 'active',             // active, inactive, out_of_stock
    'min_price' => 10.00,             // Minimum price
    'max_price' => 100.00,            // Maximum price
    'is_featured' => true,            // Featured products only
    'in_stock' => true,               // In-stock products only
    'sort_by' => 'created_at',        // Column to sort by
    'sort_order' => 'desc',           // asc or desc
];

$products = $productRepo->getPaginated($filters, 12);
```

---

### ProductImageRepository

**Interface:** `ProductImageRepositoryInterface`  
**Implementation:** `ProductImageRepository`

#### Available Methods

```php
// Get all images for a product
getByProduct(int $productId): Collection

// Get primary image for a product
getPrimaryImage(int $productId): ?ProductImage

// Create new product image
create(array $data): ProductImage

// Update product image
update(int $id, array $data): ProductImage

// Delete product image
delete(int $id): bool

// Set image as primary
setPrimary(int $productId, int $imageId): bool

// Delete all images for a product
deleteByProduct(int $productId): bool
```

---

## Service Layer

The Service Layer contains all business logic and orchestrates operations between repositories.

### CategoryService

**File:** `app/Modules/Product/Services/CategoryService.php`

#### Methods

```php
// Get all categories (returns DTOs)
getAllCategories(): Collection

// Get all active categories
getActiveCategories(): Collection

// Get root categories
getRootCategories(): Collection

// Get category by ID
getCategoryById(int $id): CategoryDTO
// Throws: InvalidArgumentException if not found

// Get category by slug
getCategoryBySlug(string $slug): CategoryDTO
// Throws: InvalidArgumentException if not found

// Get subcategories
getSubcategories(int $parentId): Collection

// Create new category
createCategory(array $data): CategoryDTO
// Throws: InvalidArgumentException on validation errors

// Update category
updateCategory(int $id, array $data): CategoryDTO
// Throws: InvalidArgumentException on validation errors

// Delete category
deleteCategory(int $id): bool
// Throws: InvalidArgumentException if has products or children
```

#### Business Rules

1. **Slug Generation** - Automatically generates slug from name if not provided
2. **Circular References** - Prevents category from being its own parent
3. **Deletion Protection** - Cannot delete category with:
   - Existing products
   - Child categories
4. **Parent Validation** - Validates parent category exists before assignment

#### Usage Example

```php
use App\Modules\Product\Services\CategoryService;

class CategoryController
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    public function store(Request $request)
    {
        try {
            $category = $this->categoryService->createCategory([
                'name' => 'New Category',
                'description' => 'Description here',
                'is_active' => true,
            ]);
            
            return response()->json($category->toArray());
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
```

---

### ProductService

**File:** `app/Modules/Product/Services/ProductService.php`

#### Methods

```php
// Get paginated products with filters
getPaginatedProducts(array $filters = [], int $perPage = 15): LengthAwarePaginator

// Get product by ID
getProductById(int $id): ProductDTO
// Throws: InvalidArgumentException if not found

// Get product by slug
getProductBySlug(string $slug): ProductDTO
// Throws: InvalidArgumentException if not found

// Get products by category (paginated)
getProductsByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator

// Get featured products
getFeaturedProducts(int $limit = 8): Collection

// Get latest products
getLatestProducts(int $limit = 8): Collection

// Search products
searchProducts(string $keyword, int $perPage = 15): LengthAwarePaginator

// Create new product
createProduct(array $data): ProductDTO

// Update product
updateProduct(int $id, array $data): ProductDTO
// Throws: InvalidArgumentException on validation errors

// Delete product
deleteProduct(int $id): bool
// Throws: InvalidArgumentException if not found

// Update stock quantity
updateStock(int $id, int $quantity): bool
// Throws: InvalidArgumentException if quantity < 0

// Add product image
addProductImage(int $productId, array $imageData): void

// Delete product image
deleteProductImage(int $imageId): bool
```

#### Business Rules

1. **Slug Generation** - Auto-generates slug from product name if not provided
2. **Stock Validation** - Prevents negative stock quantities
3. **Auto Status Update** - Changes status to 'out_of_stock' when stock reaches 0
4. **Image Management** - Deletes images from storage when product is deleted
5. **Primary Image** - First image automatically set as primary if none specified
6. **Transaction Safety** - All operations wrapped in database transactions

---

## DTOs (Data Transfer Objects)

DTOs provide type-safe data transfer between layers.

### CategoryDTO

**File:** `app/Modules/Product/DTOs/CategoryDTO.php`

#### Properties

```php
readonly class CategoryDTO
{
    public int $id;
    public string $name;
    public string $slug;
    public ?string $description;
    public ?int $parentId;
    public ?string $parentName;
    public bool $isActive;
    public int $sortOrder;
    public int $productsCount;
    public ?Collection $children;  // Collection of CategoryDTO
}
```

#### Creating from Model

```php
$dto = CategoryDTO::fromModel($category);
```

#### Converting to Array

```php
$array = $dto->toArray();
// Returns:
[
    'id' => 1,
    'name' => 'Electronics',
    'slug' => 'electronics',
    'description' => '...',
    'parent_id' => null,
    'parent_name' => null,
    'is_active' => true,
    'sort_order' => 10,
    'products_count' => 25,
    'children' => [...]
]
```

---

### ProductDTO

**File:** `app/Modules/Product/DTOs/ProductDTO.php`

#### Properties

```php
readonly class ProductDTO
{
    public int $id;
    public string $name;
    public string $slug;
    public int $categoryId;
    public string $categoryName;
    public ?string $description;
    public ?string $shortDescription;
    public float $price;
    public ?float $salePrice;
    public int $stockQuantity;
    public ?string $sku;
    public string $status;
    public bool $isFeatured;
    public ?float $weight;
    public ?array $metaData;
    public ?Collection $images;      // Collection of image arrays
    public ?string $primaryImageUrl;
    public bool $isOnSale;
    public ?int $discountPercentage;
    public float $effectivePrice;
    public bool $isInStock;
    public ?string $createdAt;
}
```

#### Image Array Structure

```php
$product->images = collect([
    [
        'id' => 1,
        'url' => 'http://example.com/storage/products/image1.jpg',
        'alt_text' => 'Product image',
        'is_primary' => true,
    ],
    // ... more images
]);
```

---

## Request Validation

All form requests extend `Illuminate\Foundation\Http\FormRequest` and provide validation rules and custom error messages.

### StoreCategoryRequest

**File:** `app/Modules/Product/Requests/StoreCategoryRequest.php`

#### Validation Rules

```php
[
    'name' => ['required', 'string', 'max:255'],
    'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
    'description' => ['nullable', 'string', 'max:1000'],
    'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
    'is_active' => ['boolean'],
    'sort_order' => ['integer', 'min:0'],
]
```

---

### StoreProductRequest

**File:** `app/Modules/Product/Requests/StoreProductRequest.php`

#### Validation Rules

```php
[
    'category_id' => ['required', 'integer', 'exists:categories,id'],
    'name' => ['required', 'string', 'max:255'],
    'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
    'description' => ['nullable', 'string', 'max:5000'],
    'short_description' => ['nullable', 'string', 'max:500'],
    'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
    'sale_price' => ['nullable', 'numeric', 'min:0', 'max:9999999.99', 'lt:price'],
    'stock_quantity' => ['required', 'integer', 'min:0'],
    'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
    'status' => ['required', Rule::in(['active', 'inactive', 'out_of_stock'])],
    'is_featured' => ['boolean'],
    'weight' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
    'meta_data' => ['nullable', 'array'],
]
```

#### Key Validations

- **sale_price** must be less than **price** (`lt:price`)
- **slug** must be lowercase with hyphens only
- **status** must be one of: active, inactive, out_of_stock
- Stock quantity cannot be negative

---

### ProductImageRequest

**File:** `app/Modules/Product/Requests/ProductImageRequest.php`

#### Validation Rules

```php
[
    'image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
    'alt_text' => ['nullable', 'string', 'max:255'],
    'is_primary' => ['boolean'],
    'sort_order' => ['integer', 'min:0'],
]
```

#### Image Requirements

- Maximum size: 2MB (2048 KB)
- Allowed formats: JPEG, JPG, PNG, WebP
- Must be a valid image file

---

## Controllers

### Customer-Facing Controller

**File:** `app/Modules/Product/Controllers/ProductController.php`

Public-facing product browsing functionality.

#### Methods

```php
// Display product listing with filters
public function index(Request $request): View

// Display single product detail
public function show(string $slug): View

// Display products by category
public function category(string $slug): View

// Search products
public function search(Request $request): View
```

---

### Admin Controllers

**File:** `app/Modules/Admin/Controllers/CategoryController.php`  
**File:** `app/Modules/Admin/Controllers/ProductController.php`

Admin panel management controllers.

#### CategoryController Methods

```php
public function index(): View                                    // List categories
public function create(): View                                   // Show create form
public function store(StoreCategoryRequest $request): RedirectResponse
public function edit(int $id): View                              // Show edit form
public function update(UpdateCategoryRequest $request, int $id): RedirectResponse
public function destroy(int $id): RedirectResponse               // Delete category
```

#### ProductController Methods

```php
public function index(Request $request): View                    // List products
public function create(): View                                   // Show create form
public function store(StoreProductRequest $request): RedirectResponse
public function edit(int $id): View                              // Show edit form
public function update(UpdateProductRequest $request, int $id): RedirectResponse
public function destroy(int $id): RedirectResponse               // Delete product
public function uploadImage(ProductImageRequest $request, int $id): RedirectResponse
public function deleteImage(int $productId, int $imageId): RedirectResponse
```

---

## Policies & Authorization

### CategoryPolicy

**File:** `app/Modules/Product/Policies/CategoryPolicy.php`

#### Authorization Rules

```php
viewAny(User $user): bool      // Everyone can view categories
view(User $user): bool          // Everyone can view single category
create(User $user): bool        // Only admins can create
update(User $user): bool        // Only admins can update
delete(User $user): bool        // Only admins can delete
```

---

### ProductPolicy

**File:** `app/Modules/Product/Policies/ProductPolicy.php`

#### Authorization Rules

```php
viewAny(?User $user): bool      // Everyone (including guests) can view
view(?User $user): bool         // Everyone can view active products
create(User $user): bool        // Only admins can create
update(User $user): bool        // Only admins can update
delete(User $user): bool        // Only admins can delete
```

#### Usage in Controllers

```php
// Authorize before action
$this->authorize('update', $product);

// Check in blade
@can('update', $product)
    <button>Edit</button>
@endcan
```

---

## Routes

### Customer Routes (Public)

**File:** `app/Modules/Product/Routes.php`

```php
GET  /products                    → ProductController@index
GET  /products/search             → ProductController@search
GET  /products/{slug}             → ProductController@show
GET  /category/{slug}             → ProductController@category
```

---

### Admin Routes (Protected - Auth Required)

**File:** `app/Modules/Admin/Routes.php`

#### Category Routes

```php
GET    /admin/categories                → CategoryController@index
GET    /admin/categories/create         → CategoryController@create
POST   /admin/categories                → CategoryController@store
GET    /admin/categories/{id}/edit      → CategoryController@edit
PUT    /admin/categories/{id}           → CategoryController@update
DELETE /admin/categories/{id}           → CategoryController@destroy
```

#### Product Routes

```php
GET    /admin/products                  → ProductController@index
GET    /admin/products/create           → ProductController@create
POST   /admin/products                  → ProductController@store
GET    /admin/products/{id}/edit        → ProductController@edit
PUT    /admin/products/{id}             → ProductController@update
DELETE /admin/products/{id}             → ProductController@destroy
POST   /admin/products/{id}/images      → ProductController@uploadImage
DELETE /admin/products/{productId}/images/{imageId} → ProductController@deleteImage
```

---

## Usage Examples

### Creating a Category

```php
use App\Modules\Product\Services\CategoryService;

// In your controller or service
$categoryService = app(CategoryService::class);

$category = $categoryService->createCategory([
    'name' => 'Electronics',
    'description' => 'Electronic devices and accessories',
    'is_active' => true,
    'sort_order' => 10,
]);

// Slug is auto-generated: 'electronics'
```

### Creating a Product

```php
use App\Modules\Product\Services\ProductService;

$productService = app(ProductService::class);

$product = $productService->createProduct([
    'category_id' => 1,
    'name' => 'iPhone 15 Pro',
    'description' => 'Latest iPhone model',
    'short_description' => 'Powerful smartphone',
    'price' => 999.99,
    'sale_price' => 899.99,
    'stock_quantity' => 50,
    'status' => 'active',
    'is_featured' => true,
]);

// Slug is auto-generated: 'iphone-15-pro'
// isOnSale: true (sale_price < price)
// discountPercentage: 10%
```

### Uploading Product Images

```php
use App\Modules\Product\Services\ProductService;

$productService = app(ProductService::class);

// Upload from request
$image = $request->file('image');
$path = $image->store('products', 'public');

$productService->addProductImage($productId, [
    'image_path' => $path,
    'alt_text' => 'iPhone 15 Pro front view',
    'is_primary' => true,
    'sort_order' => 0,
]);

// Access via: /storage/products/filename.jpg
```

### Querying Products with Filters

```php
use App\Modules\Product\Services\ProductService;

$productService = app(ProductService::class);

$filters = [
    'category_id' => 1,
    'min_price' => 100,
    'max_price' => 1000,
    'is_featured' => true,
    'in_stock' => true,
    'sort_by' => 'price',
    'sort_order' => 'asc',
];

$products = $productService->getPaginatedProducts($filters, 12);

foreach ($products as $product) {
    echo $product->name . ': $' . $product->effectivePrice;
    
    if ($product->isOnSale) {
        echo ' (Save ' . $product->discountPercentage . '%)';
    }
}
```

### Searching Products

```php
$products = $productService->searchProducts('iphone', 12);

// Searches in: name, description, short_description
```

### Getting Category Hierarchy

```php
use App\Modules\Product\Services\CategoryService;

$categoryService = app(CategoryService::class);

// Get root categories with children
$rootCategories = $categoryService->getRootCategories();

foreach ($rootCategories as $parent) {
    echo $parent->name . "\n";
    
    $children = $categoryService->getSubcategories($parent->id);
    foreach ($children as $child) {
        echo "  - " . $child->name . "\n";
    }
}
```

### Using DTOs in Blade

```php
// In controller
$product = $productService->getProductBySlug('iphone-15-pro');
return view('product.show', ['product' => $product]);
```

```blade
{{-- In blade view --}}
<h1>{{ $product->name }}</h1>
<p>{{ $product->shortDescription }}</p>

@if($product->isOnSale)
    <del>${{ $product->price }}</del>
    <strong>${{ $product->salePrice }}</strong>
    <span class="badge">Save {{ $product->discountPercentage }}%</span>
@else
    <strong>${{ $product->price }}</strong>
@endif

@if($product->isInStock)
    <span class="text-success">In Stock ({{ $product->stockQuantity }})</span>
@else
    <span class="text-danger">Out of Stock</span>
@endif

{{-- Display primary image --}}
@if($product->primaryImageUrl)
    <img src="{{ $product->primaryImageUrl }}" alt="{{ $product->name }}">
@endif

{{-- Display all images --}}
@if($product->images)
    @foreach($product->images as $image)
        <img src="{{ $image['url'] }}" alt="{{ $image['alt_text'] }}">
    @endforeach
@endif
```

---

## API Reference

### Service Method Signatures

```php
// CategoryService
getAllCategories(): Collection<CategoryDTO>
getActiveCategories(): Collection<CategoryDTO>
getRootCategories(): Collection<CategoryDTO>
getCategoryById(int $id): CategoryDTO
getCategoryBySlug(string $slug): CategoryDTO
getSubcategories(int $parentId): Collection<CategoryDTO>
createCategory(array $data): CategoryDTO
updateCategory(int $id, array $data): CategoryDTO
deleteCategory(int $id): bool

// ProductService
getPaginatedProducts(array $filters, int $perPage): LengthAwarePaginator<ProductDTO>
getProductById(int $id): ProductDTO
getProductBySlug(string $slug): ProductDTO
getProductsByCategory(int $categoryId, int $perPage): LengthAwarePaginator<ProductDTO>
getFeaturedProducts(int $limit): Collection<ProductDTO>
getLatestProducts(int $limit): Collection<ProductDTO>
searchProducts(string $keyword, int $perPage): LengthAwarePaginator<ProductDTO>
createProduct(array $data): ProductDTO
updateProduct(int $id, array $data): ProductDTO
deleteProduct(int $id): bool
updateStock(int $id, int $quantity): bool
addProductImage(int $productId, array $imageData): void
deleteProductImage(int $imageId): bool
```

### Available Filter Keys

```php
$filters = [
    'category_id'  => int,      // Filter by category ID
    'status'       => string,   // 'active' | 'inactive' | 'out_of_stock'
    'min_price'    => float,    // Minimum price
    'max_price'    => float,    // Maximum price
    'is_featured'  => bool,     // Featured products only
    'in_stock'     => bool,     // In-stock products only
    'sort_by'      => string,   // Column name to sort by
    'sort_order'   => string,   // 'asc' | 'desc'
];
```

---

## Testing

### Factory Usage

```php
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\ProductImage;

// Create a category
$category = Category::factory()->create();

// Create an inactive category
$category = Category::factory()->inactive()->create();

// Create a subcategory
$child = Category::factory()->withParent($parentId)->create();

// Create a product
$product = Product::factory()->create();

// Create a product on sale
$product = Product::factory()->onSale()->create();

// Create a featured product
$product = Product::factory()->featured()->create();

// Create an out of stock product
$product = Product::factory()->outOfStock()->create();

// Create product with images
$product = Product::factory()
    ->has(ProductImage::factory()->count(3))
    ->create();

// Create primary image
$image = ProductImage::factory()->primary()->create();
```

### Test Example

```php
use Tests\TestCase;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Services\ProductService;

class ProductServiceTest extends TestCase
{
    public function test_can_calculate_discount_percentage()
    {
        $product = Product::factory()->create([
            'price' => 100.00,
            'sale_price' => 80.00,
        ]);

        $productService = app(ProductService::class);
        $dto = $productService->getProductById($product->id);

        $this->assertTrue($dto->isOnSale);
        $this->assertEquals(20, $dto->discountPercentage);
        $this->assertEquals(80.00, $dto->effectivePrice);
    }
}
```

---

## Best Practices

### DO's ✅

1. **Always use services** - Never call repositories directly from controllers
2. **Use DTOs** - Return DTOs from services, not Eloquent models
3. **Validate input** - Use Form Request classes for all user input
4. **Use transactions** - Wrap multi-step operations in `DB::transaction()`
5. **Eager load relationships** - Prevent N+1 queries with `with()`
6. **Type hint everything** - Use strict types and return type hints
7. **Handle exceptions** - Catch `InvalidArgumentException` from services
8. **Use policies** - Check authorization with `$this->authorize()`
9. **Generate slugs** - Let services auto-generate slugs from names
10. **Use scopes** - Leverage query scopes for common filters

### DON'Ts ❌

1. **Don't bypass validation** - Always validate user input
2. **Don't use raw queries** - Use Eloquent and repositories
3. **Don't expose models** - Return DTOs from service layer
4. **Don't hardcode** - Use configuration and constants
5. **Don't ignore errors** - Always handle exceptions properly
6. **Don't forget indexes** - Ensure database performance
7. **Don't duplicate logic** - Keep business logic in services
8. **Don't skip authorization** - Always check permissions
9. **Don't cascade without caution** - Be careful with ON DELETE CASCADE
10. **Don't forget soft deletes** - Products should use soft deletes

---

## Troubleshooting

### Common Issues

#### Issue: "Class not found" errors
**Solution:** Run `composer dump-autoload`

#### Issue: Repository not resolving
**Solution:** Check `ModuleServiceProvider` bindings, ensure registered in `config/app.php`

#### Issue: Images not displaying
**Solution:** Run `php artisan storage:link` to create symbolic link

#### Issue: Validation errors not showing
**Solution:** Check Form Request `messages()` method and blade `@error` directives

#### Issue: Policies not working
**Solution:** Ensure policies registered in `AuthServiceProvider::$policies`

#### Issue: Slugs not unique
**Solution:** Use validation rule: `unique:products,slug` or auto-generate with UUID

---

## Performance Optimization

### Implemented Optimizations

1. **Database Indexes** - On frequently queried columns (slug, status, category_id)
2. **Eager Loading** - Prevent N+1 queries with `with(['category', 'images'])`
3. **Pagination** - All listings paginated to limit memory usage
4. **Query Scopes** - Efficient filtering at database level
5. **Caching Ready** - DTOs can be easily cached
6. **Soft Deletes** - Quick restoration without data loss

### Future Optimizations

- Add Redis caching for frequently accessed products
- Implement search indexing (Elasticsearch/MeiliSearch)
- Add image optimization pipeline
- Implement lazy loading for large result sets
- Add database query logging for bottleneck identification

---

## Version History

### Version 1.0.0 (February 10, 2026)
- ✅ Initial release
- ✅ Complete CRUD for categories and products
- ✅ Repository pattern implementation
- ✅ Service layer with business logic
- ✅ DTO pattern for data transfer
- ✅ Form request validation
- ✅ Policy-based authorization
- ✅ Comprehensive test factories
- ✅ Database seeders with realistic data
- ✅ Image upload functionality
- ✅ Advanced product filtering
- ✅ Search functionality
- ✅ Sale pricing and discounts
- ✅ Stock management

---

## Credits & References

- **Laravel Documentation:** https://laravel.com/docs/12.x
- **Repository Pattern:** https://martinfowler.com/eaaCatalog/repository.html
- **SOLID Principles:** https://en.wikipedia.org/wiki/SOLID
- **PSR Standards:** https://www.php-fig.org/psr/

---

## Support

For issues, questions, or contributions, please refer to the main project documentation or contact the development team.

**Module Maintainer:** Development Team  
**Last Updated:** February 10, 2026  
**Laravel Version:** 12.x  
**PHP Version:** 8.2.12

---

*This documentation is maintained as part of the Product Module and should be updated with any significant changes to the module's functionality or architecture.*
