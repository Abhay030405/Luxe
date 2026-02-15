<?php

declare(strict_types=1);

use App\Modules\Admin\Controllers\CategoryController;
use App\Modules\Admin\Controllers\CustomerController;
use App\Modules\Admin\Controllers\DashboardController;
use App\Modules\Admin\Controllers\OrderController;
use App\Modules\Admin\Controllers\ProductController;
use App\Modules\Admin\Controllers\SettingsController;
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

    // Site Settings
    Route::prefix('settings')->name('settings.')->group(function (): void {
        Route::get('/', [SettingsController::class, 'index'])
            ->name('index');

        Route::put('/', [SettingsController::class, 'update'])
            ->name('update');
    });
});
