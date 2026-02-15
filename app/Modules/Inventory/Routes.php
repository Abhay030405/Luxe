<?php

declare(strict_types=1);

use App\Modules\Inventory\Controllers\InventoryAdjustmentController;
use App\Modules\Inventory\Controllers\InventoryDashboardController;
use App\Modules\Inventory\Controllers\OrderProcessingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Inventory Module Routes
|--------------------------------------------------------------------------
|
| Admin routes for inventory management and order processing.
|
*/

// Admin routes - require authentication and admin role
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Inventory dashboard and management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryDashboardController::class, 'index'])->name('dashboard');
        Route::get('/manage', [InventoryDashboardController::class, 'manage'])->name('manage');
        Route::post('/{inventory}/adjust', [InventoryAdjustmentController::class, 'adjust'])->name('adjust');
    });

    // Order processing
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::post('/{order}/confirm', [OrderProcessingController::class, 'confirm'])->name('confirm');
        Route::post('/{order}/processing', [OrderProcessingController::class, 'markAsProcessing'])->name('processing');
        Route::post('/{order}/shipped', [OrderProcessingController::class, 'markAsShipped'])->name('shipped');
        Route::post('/{order}/delivered', [OrderProcessingController::class, 'markAsDelivered'])->name('delivered');
        Route::post('/{order}/cancel', [OrderProcessingController::class, 'cancel'])->name('cancel');
        Route::patch('/{order}/status', [OrderProcessingController::class, 'updateStatus'])->name('update-status');
    });
});
