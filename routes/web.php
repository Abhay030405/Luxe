<?php

use App\Http\Controllers\HomeController;
use App\Modules\Cart\Services\CartService;
use App\Modules\Wishlist\Services\WishlistService;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Protected Dashboard Route
Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', function (CartService $cartService, WishlistService $wishlistService) {
        $userId = auth()->id();
        $cartCount = $cartService->getCartItemCount($userId);
        $wishlistCount = $wishlistService->getWishlistCount($userId);

        return view('pages.dashboard', compact('cartCount', 'wishlistCount'));
    })->name('dashboard');
});
