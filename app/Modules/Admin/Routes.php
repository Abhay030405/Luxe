<?php

declare(strict_types=1);

use App\Modules\Admin\Controllers\CategoryController;
use App\Modules\Admin\Controllers\CustomerController;
use App\Modules\Admin\Controllers\DashboardController;
use App\Modules\Admin\Controllers\OrderController;
use App\Modules\Admin\Controllers\ProductController;
use App\Modules\Admin\Controllers\SettingsController;
use App\Modules\Admin\Controllers\VendorManagementController;
use App\Modules\Admin\Controllers\VendorOrderController;
use App\Modules\Inventory\Controllers\InventoryAdjustmentController;
use App\Modules\Inventory\Controllers\InventoryController;
use App\Modules\Inventory\Controllers\InventoryDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Module Routes
|--------------------------------------------------------------------------
|
| These routes handle admin panel functionality for managing
| products, categories, orders, and other admin operations.
| All routes require authentication and admin privileges.
|
*/

// Admin Routes (Protected - Requires Admin Privileges)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    // Admin Dashboard
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Category Management
    Route::prefix('categories')->name('categories.')->group(function (): void {
        Route::get('/', [CategoryController::class, 'index'])
            ->name('index');

        Route::get('/create', [CategoryController::class, 'create'])
            ->name('create');

        Route::post('/', [CategoryController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [CategoryController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [CategoryController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [CategoryController::class, 'destroy'])
            ->name('destroy');
    });

    // Product Management
    Route::prefix('products')->name('products.')->group(function (): void {
        Route::get('/', [ProductController::class, 'index'])
            ->name('index');

        Route::get('/create', [ProductController::class, 'create'])
            ->name('create');

        Route::post('/', [ProductController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [ProductController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [ProductController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [ProductController::class, 'destroy'])
            ->name('destroy');

        // Product Image Management
        Route::post('/{id}/images', [ProductController::class, 'uploadImage'])
            ->name('images.upload');

        Route::delete('/{productId}/images/{imageId}', [ProductController::class, 'deleteImage'])
            ->name('images.delete');
    });

    // Order Management
    Route::prefix('orders')->name('orders.')->group(function (): void {
        Route::get('/', [OrderController::class, 'index'])
            ->name('index');

        Route::get('/{id}', [OrderController::class, 'show'])
            ->name('show');

        Route::put('/{id}/status', [OrderController::class, 'updateStatus'])
            ->name('update-status');

        Route::post('/{id}/cancel', [OrderController::class, 'cancel'])
            ->name('cancel');
    });

    // Customer Management
    Route::prefix('customers')->name('customers.')->group(function (): void {
        Route::get('/', [CustomerController::class, 'index'])
            ->name('index');

        Route::get('/{id}', [CustomerController::class, 'show'])
            ->name('show');
    });

    // Vendor Management
    Route::prefix('vendors')->name('vendors.')->group(function (): void {
        // Vendor Applications
        Route::get('/applications', [VendorManagementController::class, 'applications'])
            ->name('applications');

        Route::get('/applications/{id}', [VendorManagementController::class, 'showApplication'])
            ->name('applications.show');

        Route::post('/applications/{id}/approve', [VendorManagementController::class, 'approveApplication'])
            ->name('applications.approve');

        Route::post('/applications/{id}/reject', [VendorManagementController::class, 'rejectApplication'])
            ->name('applications.reject');

        // Approved Vendors
        Route::get('/', [VendorManagementController::class, 'index'])
            ->name('index');

        Route::get('/{id}', [VendorManagementController::class, 'show'])
            ->name('show');

        Route::post('/{id}/suspend', [VendorManagementController::class, 'suspend'])
            ->name('suspend');

        Route::post('/{id}/reactivate', [VendorManagementController::class, 'reactivate'])
            ->name('reactivate');
    });

    // Vendor Order Monitoring
    Route::prefix('vendor-orders')->name('vendor-orders.')->group(function (): void {
        Route::get('/', [VendorOrderController::class, 'index'])
            ->name('index');

        Route::get('/{id}', [VendorOrderController::class, 'show'])
            ->name('show');
    });

    // Site Settings
    Route::prefix('settings')->name('settings.')->group(function (): void {
        Route::get('/', [SettingsController::class, 'index'])
            ->name('index');

        Route::put('/', [SettingsController::class, 'update'])
            ->name('update');
    });

    // Inventory Management
    Route::prefix('inventory')->name('inventory.')->group(function (): void {
        // Inventory Dashboard
        Route::get('/dashboard', [InventoryDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/manage', [InventoryDashboardController::class, 'manage'])
            ->name('manage');

        // Inventory CRUD
        Route::get('/', [InventoryController::class, 'index'])
            ->name('index');

        Route::get('/create', [InventoryController::class, 'create'])
            ->name('create');

        Route::post('/', [InventoryController::class, 'store'])
            ->name('store');

        Route::get('/{inventory}', [InventoryController::class, 'show'])
            ->name('show');

        Route::get('/{inventory}/edit', [InventoryController::class, 'edit'])
            ->name('edit');

        Route::put('/{inventory}', [InventoryController::class, 'update'])
            ->name('update');

        // Inventory Adjustments
        Route::post('/{inventory}/adjust', [InventoryAdjustmentController::class, 'adjust'])
            ->name('adjust');

        // Bulk Operations
        Route::post('/bulk/update-thresholds', [InventoryController::class, 'bulkUpdateThresholds'])
            ->name('bulk.update-thresholds');
    });
});
