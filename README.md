<div align="center">
  <h1>🛍️ Luxe E-Commerce Platform</h1>
  <p><strong>A Modern, Scalable E-Commerce Solution Built with Laravel 12</strong></p>
  
  <p>
    <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
    <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2">
    <img src="https://img.shields.io/badge/TailwindCSS-4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS 4">
    <img src="https://img.shields.io/badge/Vite-7.0-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite 7">
  </p>

  <p>
    <strong>Live Demo:</strong> https://johnedyran.com/<i>Coming Soon</i>
  </p>
</div>

---

## 📋 Table of Contents

- [About Luxe](#about-luxe)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Architecture](#architecture)
- [Installation](#installation)
- [Database Schema](#database-schema)
- [Module Overview](#module-overview)
- [Screenshots](#screenshots)
- [Testing](#testing)
- [Roadmap](#roadmap)
- [Contributing](#contributing)
- [License](#license)

---

## 🌟 About Luxe

**Luxe** is a feature-rich, enterprise-grade e-commerce platform built with Laravel 12. Designed with a modular architecture, Luxe provides a scalable foundation for online retail businesses, offering seamless shopping experiences from product browsing to checkout and order management.

### Why Luxe?

- **Modular Architecture**: Clean separation of concerns with dedicated modules for each business domain
- **Modern Tech Stack**: Built on Laravel 12, TailwindCSS 4, and Vite 7
- **Production-Ready**: Comprehensive validation, security policies, and error handling
- **Admin Dashboard**: Full-featured admin panel for managing products, orders, and customers
- **Responsive Design**: Mobile-first UI with beautiful, modern components

---

## ✨ Features

### 🛒 Customer Features

#### **User Management**
- ✅ User registration and authentication
- ✅ Profile management (name, email, phone)
- ✅ Secure password change functionality
- ✅ Email verification
- ✅ Session management

#### **Product Browsing**
- ✅ Product catalog with grid layout
- ✅ Advanced filtering (category, price range, ratings)
- ✅ Product search functionality
- ✅ Product detail pages with image galleries
- ✅ Related products suggestions
- ✅ Sale badges and stock indicators
- ✅ Product ratings and reviews display

#### **Shopping Cart**
- ✅ Add/remove products from cart
- ✅ Quantity adjustment
- ✅ Real-time price calculations
- ✅ Cart persistence across sessions
- ✅ Discount code application
- ✅ Order summary with tax and shipping

#### **Address Management**
- ✅ Multiple delivery addresses per user
- ✅ Add, edit, and delete addresses
- ✅ Set default shipping address
- ✅ Complete address validation (street, city, state, postal code, country)
- ✅ Phone number per address

#### **Checkout & Orders**
- ✅ Multi-step checkout process
- ✅ Address selection during checkout
- ✅ Payment method integration (ready)
- ✅ Order placement and confirmation
- ✅ Order history and tracking
- ✅ Order detail view with item breakdown
- ✅ Order status updates

---

### 👨‍💼 Admin Features

#### **Dashboard**
- ✅ Sales analytics and statistics
- ✅ Recent orders overview
- ✅ Customer insights
- ✅ Quick actions panel

#### **Product Management**
- ✅ Create, read, update, delete (CRUD) products
- ✅ Multiple product images
- ✅ Category assignment
- ✅ Price and stock management
- ✅ Product status (active/inactive)
- ✅ Bulk operations

#### **Category Management**
- ✅ Hierarchical category structure
- ✅ Category CRUD operations
- ✅ Category icons and descriptions
- ✅ SEO-friendly slugs

#### **Order Management**
- ✅ View all orders
- ✅ Order status management (pending, processing, shipped, delivered, cancelled)
- ✅ Order details and customer information
- ✅ Order fulfillment workflow

#### **Customer Management**
- ✅ View all customers
- ✅ Customer profiles and order history
- ✅ Customer status management
- ✅ Search and filter customers

#### **Inventory Management**
- ✅ Stock tracking
- ✅ Low stock alerts (ready)
- ✅ Inventory history

---

## 🚀 Technology Stack

### Backend
- **Framework**: Laravel 12.x
- **PHP**: 8.2+
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum (ready for API)
- **Validation**: Form Request Classes
- **Authorization**: Policies & Gates

### Frontend
- **CSS Framework**: TailwindCSS 4.0
- **JavaScript**: Alpine.js (via CDN)
- **Build Tool**: Vite 7.0
- **HTTP Client**: Axios
- **Templating**: Blade Components

### Development Tools
- **Code Quality**: Laravel Pint (PSR-12)
- **Testing**: PHPUnit, Faker
- **Debugging**: Laravel Telescope (optional)
- **Container**: Laravel Sail (Docker)
- **Package Manager**: Composer, NPM

---

## 🏗️ Architecture

### Modular Structure

Luxe follows a **modular monolith** architecture pattern, with each business domain isolated into its own module:

```
app/Modules/
├── Admin/          # Admin dashboard and management
├── Auth/           # Authentication and authorization
├── Cart/           # Shopping cart functionality
├── Inventory/      # Stock and inventory management
├── Order/          # Order processing and management
├── Payment/        # Payment gateway integrations
├── Product/        # Product catalog and details
└── User/           # User profiles and addresses
```

### Module Anatomy

Each module follows a consistent structure:

```
ModuleName/
├── Controllers/    # HTTP request handlers
├── Requests/       # Form validation classes
├── Services/       # Business logic layer
├── Models/         # Eloquent models
├── Routes/         # Module-specific routes
└── Views/          # Blade templates (if needed)
```

### Design Patterns Used

- **Repository Pattern**: Clean data access layer
- **Service Layer Pattern**: Encapsulated business logic
- **Form Request Pattern**: Dedicated validation classes
- **Policy Pattern**: Authorization logic separation
- **Factory Pattern**: Test data generation
- **DTO Pattern**: Type-safe data transfer objects

---

## 📦 Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- MySQL/PostgreSQL
- Git

### Step-by-Step Setup

1. **Clone the Repository**
   ```bash
   git clone <repository-url> luxe
   cd luxe
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node Dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database**
   
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=luxe_ecommerce
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run Migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed Database (Optional)**
   ```bash
   php artisan db:seed
   ```

8. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

9. **Build Frontend Assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

10. **Start Development Server**
    ```bash
    php artisan serve
    ```

11. **Access the Application**
    - Frontend: `http://localhost:8000`
    - Admin: `http://localhost:8000/admin`

---

## 🗄️ Database Schema

### Core Tables

#### Users & Profiles
- `users` - User accounts
- `user_profiles` - Extended user information
- `addresses` - Multiple delivery addresses per user

#### Products
- `categories` - Product categories (hierarchical)
- `products` - Product catalog
- `product_images` - Multiple images per product

#### Shopping
- `cart_items` - Shopping cart items (user session)
- `orders` - Customer orders
- `order_items` - Individual items within orders

#### System
- `sessions` - User session management
- `cache` - Application cache
- `jobs` - Queue jobs
- `failed_jobs` - Failed queue jobs

### Relationships

```
users (1) ──< (n) addresses
users (1) ──< (n) cart_items
users (1) ──< (n) orders

categories (1) ──< (n) products
products (1) ──< (n) product_images
products (1) ──< (n) cart_items
products (1) ──< (n) order_items

orders (1) ──< (n) order_items
```

---

## 📂 Module Overview

### 🔐 Auth Module
Handles user authentication, registration, and session management.

**Key Features:**
- User registration with validation
- Email/password login
- Logout functionality
- Password reset (ready)

### 👤 User Module
Manages customer profiles and delivery addresses.

**Key Features:**
- Profile viewing and editing
- Password change
- Address book management
- Default address selection

### 🛍️ Product Module
Displays product catalog and details to customers.

**Key Features:**
- Product listing with pagination
- Advanced filtering and sorting
- Product detail pages
- Related products

### 🛒 Cart Module
Shopping cart functionality for customers.

**Key Features:**
- Add to cart
- Update quantities
- Remove items
- Persistent cart storage

### 📦 Order Module
Complete order lifecycle management.

**Key Features:**
- Checkout process
- Order placement
- Order history
- Order status tracking

### 💳 Payment Module
Payment processing integration (ready for implementation).

**Prepared For:**
- Stripe integration
- PayPal integration
- Cash on delivery
- Bank transfer

### 🏢 Admin Module
Complete administrative dashboard.

**Key Features:**
- Product management
- Category management
- Order management
- Customer management
- Analytics dashboard

### 📊 Inventory Module
Stock and inventory tracking.

**Key Features:**
- Stock level management
- Inventory updates
- Stock alerts (ready)

---

## 🎨 Screenshots

> **Note:** Screenshots will be added here once the live deployment is ready.

### Customer Interface
- Homepage
- Product Listing
- Product Details
- Shopping Cart
- Checkout Process
- User Dashboard

### Admin Panel
- Admin Dashboard
- Product Management
- Order Management
- Customer Management

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Test Coverage

- Unit tests for models and services
- Feature tests for controllers and routes
- Database factories for test data
- Policy tests for authorization

---

## 🗺️ Roadmap

### Phase 4 (In Progress)
- [ ] Payment gateway integration (Stripe/PayPal)
- [ ] Email notifications (order confirmation, shipping updates)
- [ ] Invoice generation (PDF)

### Phase 5 (Planned)
- [ ] Product reviews and ratings system
- [ ] Wishlist functionality
- [ ] Product comparison
- [ ] Advanced search with filters

### Phase 6 (Future)
- [ ] Multi-vendor marketplace
- [ ] Subscription products
- [ ] Loyalty points program
- [ ] Live chat support
- [ ] Mobile app (API-first)

---

## 🤝 Contributing

We welcome contributions! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Run Laravel Pint before committing: `./vendor/bin/pint`
- Write tests for new features
- Update documentation as needed

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).

---

## 👨‍💻 Development Team

Built with ❤️ using Laravel 12

---

## 📞 Support

For support, please open an issue in the GitHub repository or contact the development team.

---

<div align="center">
  <p><strong>Luxe E-Commerce Platform</strong></p>
  <p>Empowering Online Retail Experiences</p>
</div>