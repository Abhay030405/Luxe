<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Protected Dashboard Route
Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');
});
