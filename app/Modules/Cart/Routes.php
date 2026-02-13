<?php

declare(strict_types=1);

use App\Modules\Cart\Controllers\CartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cart Module Routes
|--------------------------------------------------------------------------
|
| These routes handle shopping cart functionality for authenticated users.
| All routes require authentication.
|
*/

Route::middleware('auth')->group(function (): void {
    // Cart page
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    // Add to cart
    Route::post('/cart', [CartController::class, 'store'])
        ->name('cart.store');

    // Update cart item quantity
    Route::put('/cart/{cartItemId}', [CartController::class, 'update'])
        ->name('cart.update');

    // Remove item from cart
    Route::delete('/cart/{cartItemId}', [CartController::class, 'destroy'])
        ->name('cart.destroy');

    // Clear cart
    Route::delete('/cart', [CartController::class, 'clear'])
        ->name('cart.clear');

    // AJAX endpoints
    Route::prefix('ajax/cart')->name('cart.ajax.')->group(function (): void {
        // Add to cart via AJAX
        Route::post('/', [CartController::class, 'storeAjax'])
            ->name('store');

        // Update cart item via AJAX
        Route::put('/{cartItemId}', [CartController::class, 'updateAjax'])
            ->name('update');

        // Remove item via AJAX
        Route::delete('/{cartItemId}', [CartController::class, 'destroyAjax'])
            ->name('destroy');

        // Get cart count
        Route::get('/count', [CartController::class, 'count'])
            ->name('count');
    });
});
