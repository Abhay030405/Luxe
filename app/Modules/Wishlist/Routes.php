<?php

declare(strict_types=1);

use App\Modules\Wishlist\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Wishlist Module Routes
|--------------------------------------------------------------------------
|
| These routes handle wishlist functionality for authenticated users.
| All routes require authentication.
|
*/

Route::middleware('auth')->group(function (): void {
    // Wishlist page
    Route::get('/wishlist', [WishlistController::class, 'index'])
        ->name('wishlist.index');

    // Add to wishlist
    Route::post('/wishlist', [WishlistController::class, 'store'])
        ->name('wishlist.store');

    // Toggle wishlist (add/remove)
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])
        ->name('wishlist.toggle');

    // Remove from wishlist
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy'])
        ->name('wishlist.destroy')
        ->whereNumber('productId');

    // Remove from wishlist (AJAX)
    Route::post('/wishlist/{productId}/remove', [WishlistController::class, 'destroyAjax'])
        ->name('wishlist.remove')
        ->whereNumber('productId');

    // Check if product is in wishlist
    Route::post('/wishlist/check', [WishlistController::class, 'check'])
        ->name('wishlist.check');

    // Get wishlist count
    Route::get('/wishlist/count', [WishlistController::class, 'count'])
        ->name('wishlist.count');

    // Clear wishlist
    Route::post('/wishlist/clear', [WishlistController::class, 'clear'])
        ->name('wishlist.clear');
});
