<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Protected Dashboard Route
Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');
});
