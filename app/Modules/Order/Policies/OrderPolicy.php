<?php

declare(strict_types=1);

namespace App\Modules\Order\Policies;

use App\Models\User;
use App\Modules\Order\Models\Order;

/**
 * OrderPolicy defines authorization rules for orders.
 * CRITICAL: Ensures users can only access their own orders.
 */
class OrderPolicy
{
    /**
     * Determine if the user can view any orders.
     * Only admins can view all orders.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine if the user can view the order.
     * Users can only view their own orders.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->is_admin;
    }

    /**
     * Determine if the user can update the order.
     * Only admins can update orders (change status).
     */
    public function update(User $user, Order $order): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine if the user can cancel the order.
     * Users can cancel their own orders if they're cancellable.
     * Admins can cancel any order.
     */
    public function cancel(User $user, Order $order): bool
    {
        if ($user->is_admin) {
            return true;
        }

        return $user->id === $order->user_id && $order->canBeCancelled();
    }

    /**
     * Determine if the user can delete the order.
     * Only admins can delete orders.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine if the user can restore the order.
     * Only admins can restore deleted orders.
     */
    public function restore(User $user, Order $order): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine if the user can permanently delete the order.
     * Only admins can force delete orders.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return $user->is_admin;
    }
}
