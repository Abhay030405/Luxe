<?php

namespace App\Providers;

use App\Modules\Auth\Repositories\Contracts\UserRepositoryInterface;
use App\Modules\Auth\Repositories\UserRepository;
use App\Modules\User\Repositories\AddressRepository;
use App\Modules\User\Repositories\Contracts\AddressRepositoryInterface;
use App\Modules\User\Repositories\Contracts\ProfileRepositoryInterface;
use App\Modules\User\Repositories\ProfileRepository;
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
