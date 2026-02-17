<?php

namespace App\Providers;

use App\Modules\Auth\Repositories\Contracts\UserRepositoryInterface;
use App\Modules\Auth\Repositories\UserRepository;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Cart\Repositories\Contracts\CartRepositoryInterface;
use App\Modules\Product\Repositories\CategoryRepository;
use App\Modules\Product\Repositories\Contracts\CategoryRepositoryInterface;
use App\Modules\Product\Repositories\Contracts\ProductImageRepositoryInterface;
use App\Modules\Product\Repositories\Contracts\ProductRepositoryInterface;
use App\Modules\Product\Repositories\ProductImageRepository;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\User\Repositories\AddressRepository;
use App\Modules\User\Repositories\Contracts\AddressRepositoryInterface;
use App\Modules\User\Repositories\Contracts\ProfileRepositoryInterface;
use App\Modules\User\Repositories\ProfileRepository;
use App\Modules\Vendor\Repositories\Contracts\VendorRepositoryInterface;
use App\Modules\Vendor\Repositories\VendorRepository;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Auth module repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Register User module repositories
        $this->app->bind(ProfileRepositoryInterface::class, ProfileRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);

        // Register Product module repositories
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductImageRepositoryInterface::class, ProductImageRepository::class);

        // Register Cart module repositories
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);

        // Register Vendor module repositories
        $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
