<?php

declare(strict_types=1);

use App\Modules\Order\Controllers\CheckoutController;
use App\Modules\Order\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Order Module Routes
|--------------------------------------------------------------------------
|
| These routes handle checkout and order management for authenticated users.
| All routes require authentication.
|
*/

Route::middleware('auth')->group(function (): void {
    /*
    |--------------------------------------------------------------------------
    | Checkout Routes
    |--------------------------------------------------------------------------
    |
    | Handles the checkout process from cart to order placement.
    |
    */

    // Display checkout page
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    // Place order (submit checkout)
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])
        ->name('checkout.place');

    /*
    |--------------------------------------------------------------------------
    | Order Management Routes
    |--------------------------------------------------------------------------
    |
    | Handles order history, details, and order actions.
    |
    */

    // Order history page
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    // Order details page
    Route::get('/orders/{id}', [OrderController::class, 'show'])
        ->name('orders.show')
        ->whereNumber('id');

    // Cancel order
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])
        ->name('orders.cancel')
        ->whereNumber('id');

    // Track order by order number
    Route::get('/orders/track/{orderNumber}', [OrderController::class, 'track'])
        ->name('orders.track');
});
