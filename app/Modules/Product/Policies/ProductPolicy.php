<?php

declare(strict_types=1);

namespace App\Modules\Product\Policies;

use App\Models\User;
use App\Modules\Product\Models\Product;

class ProductPolicy
{
    /**
     * Determine if the user can view any products.
     */
    public function viewAny(?User $user): bool
    {
        // Everyone can view products
        return true;
    }

    /**
     * Determine if the user can view the product.
     */
    public function view(?User $user, Product $product): bool
    {
        // Everyone can view active products
        return $product->status === 'active' || ($user && ($user->is_admin ?? false));
    }

    /**
     * Determine if the user can create products.
     */
    public function create(User $user): bool
    {
        // Only admins can create products
        return $user->is_admin ?? false;
    }

    /**
     * Determine if the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->is_admin ?? false;
    }

    /**
     * Determine if the user can delete the product.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->is_admin ?? false;
    }
}
