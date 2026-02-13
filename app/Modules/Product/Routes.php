<?php

declare(strict_types=1);

use App\Modules\Product\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Product Module Routes (Customer-Facing)
|--------------------------------------------------------------------------
|
| These routes handle product browsing, viewing, and search functionality
| for customers. No authentication required.
|
*/

// Public Product Routes
Route::prefix('products')->name('products.')->group(function (): void {
    // Product listing
    Route::get('/', [ProductController::class, 'index'])
        ->name('index');

    // Product search
    Route::get('/search', [ProductController::class, 'search'])
        ->name('search');

    // Product detail by slug
    Route::get('/{slug}', [ProductController::class, 'show'])
        ->name('show');
});

// Category Routes
Route::prefix('category')->name('category.')->group(function (): void {
    Route::get('/{slug}', [ProductController::class, 'category'])
        ->name('show');
});
