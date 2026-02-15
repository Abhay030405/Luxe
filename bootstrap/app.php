<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            // Register Auth module routes
            Route::middleware('web')
                ->group(base_path('app/Modules/Auth/Routes.php'));

            // Register User module routes
            Route::middleware('web')
                ->group(base_path('app/Modules/User/Routes.php'));

            // Register Product module routes
            Route::middleware('web')
                ->group(base_path('app/Modules/Product/Routes.php'));

            // Register Cart module routes
            Route::middleware('web')
                ->group(base_path('app/Modules/Cart/Routes.php'));

            // Register Wishlist module routes
            Route::middleware('web')
                ->group(base_path('app/Modules/Wishlist/Routes.php'));

            // Register Order module routes
            Route::middleware('web')
                ->group(base_path('app/Modules/Order/Routes.php'));

            // Register Admin module routes
            Route::middleware('web')
                ->group(base_path('app/Modules/Admin/Routes.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
