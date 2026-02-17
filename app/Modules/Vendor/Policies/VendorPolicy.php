<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Policies;

use App\Models\User;
use App\Modules\Vendor\Models\Vendor;

class VendorPolicy
{
    /**
     * Determine whether the user can view any vendors.
     */
    public function viewAny(User $user): bool
    {
        // Anyone can view the list of approved vendors
        return true;
    }

    /**
     * Determine whether the user can view the vendor.
     */
    public function view(User $user, Vendor $vendor): bool
    {
        // Owner or admin can view any vendor
        if ($user->is_admin || $user->id === $vendor->user_id) {
            return true;
        }

        // Others can only view approved vendors
        return $vendor->status === 'approved';
    }

    /**
     * Determine whether the user can create vendors.
     */
    public function create(User $user): bool
    {
        // User must not already have a vendor account
        return ! $user->isVendor();
    }

    /**
     * Determine whether the user can update the vendor.
     */
    public function update(User $user, Vendor $vendor): bool
    {
        // Owner can update their own vendor, admin can update any
        return $user->is_admin || $user->id === $vendor->user_id;
    }

    /**
     * Determine whether the user can delete the vendor.
     */
    public function delete(User $user, Vendor $vendor): bool
    {
        // Only admins can delete vendors
        return $user->is_admin;
    }

    /**
     * Determine whether the user can approve vendors.
     */
    public function approve(User $user): bool
    {
        // Only admins can approve vendors
        return $user->is_admin;
    }

    /**
     * Determine whether the user can reject vendors.
     */
    public function reject(User $user): bool
    {
        // Only admins can reject vendors
        return $user->is_admin;
    }

    /**
     * Determine whether the user can suspend vendors.
     */
    public function suspend(User $user): bool
    {
        // Only admins can suspend vendors
        return $user->is_admin;
    }

    /**
     * Determine whether the user can reactivate vendors.
     */
    public function reactivate(User $user): bool
    {
        // Only admins can reactivate vendors
        return $user->is_admin;
    }

    /**
     * Determine whether the user can manage vendor products.
     */
    public function manageProducts(User $user, Vendor $vendor): bool
    {
        // Owner can manage their own vendor's products if approved
        return $user->id === $vendor->user_id && $vendor->status === 'approved';
    }

    /**
     * Determine whether the user can access vendor dashboard.
     */
    public function accessDashboard(User $user, Vendor $vendor): bool
    {
        // Owner can access their own vendor dashboard
        return $user->id === $vendor->user_id;
    }
}
