<?php

declare(strict_types=1);

namespace App\Modules\Order\Repositories;

use App\Modules\Order\DTOs\CreateOrderDTO;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Shared\Enums\OrderStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Repository for Order data access.
 * Handles all database operations for orders.
 */
class OrderRepository
{
    /**
     * Find order by ID.
     */
    public function findById(int $id): ?Order
    {
        return Order::find($id);
    }

    /**
     * Find order by ID or fail.
     */
    public function findByIdOrFail(int $id): Order
    {
        return Order::findOrFail($id);
    }

    /**
     * Find order by order number.
     */
    public function findByOrderNumber(string $orderNumber): ?Order
    {
        return Order::where('order_number', $orderNumber)->first();
    }

    /**
     * Get orders for a specific user with pagination.
     */
    public function getUserOrders(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Order::forUser($userId)
            ->with(['items.product'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get recent orders for a user.
     */
    public function getRecentUserOrders(int $userId, int $limit = 5): Collection
    {
        return Order::forUser($userId)
            ->with(['items.product'])
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get orders by status for a user.
     */
    public function getUserOrdersByStatus(int $userId, OrderStatus $status): Collection
    {
        return Order::forUser($userId)
            ->withStatus($status)
            ->with(['items.product'])
            ->latest('created_at')
            ->get();
    }

    /**
     * Check if user owns the order.
     */
    public function userOwnsOrder(int $userId, int $orderId): bool
    {
        return Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Create a new order with items in a transaction.
     * This is the critical atomic operation.
     */
    public function createOrderWithItems(CreateOrderDTO $dto): Order
    {
        return DB::transaction(function () use ($dto) {
            // Step 1: Create the order
            $order = Order::create($dto->toOrderArray());

            // Step 2: Create order items
            $orderItems = array_map(
                fn ($item) => $item->toOrderItemArray($order->id),
                $dto->items
            );

            OrderItem::insert($orderItems);

            // Step 3: Reload order with items
            return $order->fresh(['items.product']);
        });
    }

    /**
     * Update order status.
     */
    public function updateStatus(int $orderId, OrderStatus $status, ?string $adminNotes = null): bool
    {
        $data = ['status' => $status];

        if ($adminNotes !== null) {
            $data['admin_notes'] = $adminNotes;
        }

        return Order::where('id', $orderId)->update($data) > 0;
    }

    /**
     * Cancel an order.
     */
    public function cancelOrder(int $orderId, string $reason = ''): bool
    {
        $order = $this->findByIdOrFail($orderId);

        if (! $order->canBeCancelled()) {
            return false;
        }

        return $this->updateStatus(
            $orderId,
            OrderStatus::Cancelled,
            'Cancellation reason: '.$reason
        );
    }

    /**
     * Get all orders (admin use).
     */
    public function getAllOrders(int $perPage = 20): LengthAwarePaginator
    {
        return Order::with(['user', 'items.product'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get orders by status (admin use).
     */
    public function getOrdersByStatus(OrderStatus $status, int $perPage = 20): LengthAwarePaginator
    {
        return Order::withStatus($status)
            ->with(['user', 'items.product'])
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get order statistics for a user.
     */
    public function getUserOrderStats(int $userId): array
    {
        $orders = Order::forUser($userId)->get();

        return [
            'total_orders' => $orders->count(),
            'total_spent' => $orders->sum('total_amount'),
            'pending_orders' => $orders->where('status', OrderStatus::Pending)->count(),
            'completed_orders' => $orders->where('status', OrderStatus::Delivered)->count(),
            'cancelled_orders' => $orders->where('status', OrderStatus::Cancelled)->count(),
        ];
    }

    /**
     * Get total revenue (admin use).
     */
    public function getTotalRevenue(): float
    {
        return (float) Order::whereIn('status', [
            OrderStatus::Confirmed,
            OrderStatus::Processing,
            OrderStatus::Shipped,
            OrderStatus::Delivered,
        ])->sum('total_amount');
    }

    /**
     * Generate unique order number.
     */
    public function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-'.date('Y').'-'.str_pad((string) rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while ($this->findByOrderNumber($orderNumber) !== null);

        return $orderNumber;
    }

    /**
     * Delete order (soft delete).
     */
    public function delete(int $orderId): bool
    {
        return Order::where('id', $orderId)->delete() > 0;
    }

    /**
     * Get orders within date range.
     */
    public function getOrdersBetweenDates(string $startDate, string $endDate): Collection
    {
        return Order::betweenDates($startDate, $endDate)
            ->with(['user', 'items.product'])
            ->get();
    }
}
