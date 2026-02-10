<?php

declare(strict_types=1);

use App\Modules\User\Controllers\AddressController;
use App\Modules\User\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Module Routes
|--------------------------------------------------------------------------
|
| These routes handle user profile management, password changes, and
| address management. All routes are protected by the auth middleware.
|
*/

// Authenticated Routes (only accessible when logged in)
Route::middleware('auth')->group(function (): void {
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function (): void {
        Route::get('/', [ProfileController::class, 'show'])
            ->name('show');

        Route::get('/edit', [ProfileController::class, 'edit'])
            ->name('edit');

        Route::put('/update', [ProfileController::class, 'update'])
            ->name('update');

        Route::get('/password', [ProfileController::class, 'editPassword'])
            ->name('password.edit');

        Route::put('/password', [ProfileController::class, 'updatePassword'])
            ->name('password.update');
    });

    // Address Routes
    Route::prefix('addresses')->name('addresses.')->group(function (): void {
        Route::get('/', [AddressController::class, 'index'])
            ->name('index');

        Route::get('/create', [AddressController::class, 'create'])
            ->name('create');

        Route::post('/', [AddressController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [AddressController::class, 'edit'])
            ->name('edit')
            ->where('id', '[0-9]+');

        Route::put('/{id}', [AddressController::class, 'update'])
            ->name('update')
            ->where('id', '[0-9]+');

        Route::delete('/{id}', [AddressController::class, 'destroy'])
            ->name('destroy')
            ->where('id', '[0-9]+');

        Route::post('/{id}/set-default', [AddressController::class, 'setDefault'])
            ->name('set-default')
            ->where('id', '[0-9]+');
    });
});
