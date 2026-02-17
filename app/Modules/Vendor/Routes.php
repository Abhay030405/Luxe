<?php

declare(strict_types=1);

use App\Modules\Vendor\Controllers\VendorApplicationController;
use App\Modules\Vendor\Controllers\VendorDashboardController;
use App\Modules\Vendor\Controllers\VendorOrderController;
use App\Modules\Vendor\Controllers\VendorProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Vendor Module Routes
|--------------------------------------------------------------------------
|
| These routes handle vendor application, vendor dashboard, and vendor
| product management functionality.
|
*/

// Public Vendor Routes
Route::prefix('vendor')->name('vendor.')->group(function (): void {
    // Public vendor store pages (anyone can view)
    Route::get('/store/{slug}', [VendorDashboardController::class, 'store'])
        ->name('store.show');
});

// Protected Vendor Application Routes (Requires Authentication)
Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function (): void {
    // Vendor application form (requires authentication)
    Route::get('/apply/form', [VendorApplicationController::class, 'create'])
        ->name('application.form');

    Route::post('/apply', [VendorApplicationController::class, 'store'])
        ->name('application.store');

    // Check application status (for authenticated users)
    Route::get('/application/status', [VendorApplicationController::class, 'checkStatus'])
        ->name('application.status');
});

// Protected Vendor Routes (Requires Authentication + Vendor Role)
Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function (): void {
    // Vendor Dashboard
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [VendorDashboardController::class, 'profile'])
        ->name('profile');

    // Vendor Product Management
    Route::prefix('products')->name('products.')->group(function (): void {
        Route::get('/', [VendorProductController::class, 'index'])
            ->name('index');

        Route::get('/create', [VendorProductController::class, 'create'])
            ->name('create');

        Route::post('/', [VendorProductController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [VendorProductController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [VendorProductController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [VendorProductController::class, 'destroy'])
            ->name('destroy');

        // Image upload routes
        Route::post('/{id}/images', [VendorProductController::class, 'uploadImage'])
            ->name('images.upload');

        Route::delete('/{productId}/images/{imageId}', [VendorProductController::class, 'deleteImage'])
            ->name('images.delete');
    });

    // Vendor Order Management
    Route::prefix('orders')->name('orders.')->group(function (): void {
        Route::get('/', [VendorOrderController::class, 'index'])
            ->name('index');

        Route::get('/{id}', [VendorOrderController::class, 'show'])
            ->name('show');

        Route::post('/{id}/accept', [VendorOrderController::class, 'accept'])
            ->name('accept');

        Route::post('/{id}/pack', [VendorOrderController::class, 'pack'])
            ->name('pack');

        Route::post('/{id}/ship', [VendorOrderController::class, 'ship'])
            ->name('ship');

        Route::post('/{id}/deliver', [VendorOrderController::class, 'deliver'])
            ->name('deliver');

        Route::post('/{id}/cancel', [VendorOrderController::class, 'cancel'])
            ->name('cancel');

        Route::post('/{id}/reject', [VendorOrderController::class, 'reject'])
            ->name('reject');
    });
});
