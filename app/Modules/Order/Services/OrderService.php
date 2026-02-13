<?php

declare(strict_types=1);

namespace App\Modules\Order\Services;

use App\Modules\Order\DTOs\OrderSummaryDTO;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Repositories\OrderRepository;
use App\Shared\Enums\OrderStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * OrderService handles order management operations.
 * Provides business logic layer for order retrieval, updates, and cancellation.
 */
class OrderService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {}

    /**
     * Get order by ID with authorization check.
     */
    public function getOrder(int $orderId, int $userId): Order
    {
        $order = $this->orderRepository->findByIdOrFail($orderId);

        // Security check: ensure user owns the order
        if ($order->user_id !== $userId) {
            throw new InvalidArgumentException('Order not found or access denied.');
        }

        return $order;
    }

    /**
     * Get order details with summary.
     */
    public function getOrderWithSummary(int $orderId, int $userId): array
    {
        $order = $this->getOrder($orderId, $userId);
        $summary = OrderSummaryDTO::fromOrder($order);

        return [
            'order' => $order,
            'summary' => $summary,
        ];
    }

    /**
     * Get user's order history with pagination.
     */
    public function getUserOrders(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->orderRepository->getUserOrders($userId, $perPage);
    }

    /**
     * Get user's recent orders.
     */
    public function getRecentOrders(int $userId, int $limit = 5): Collection
    {
        return $this->orderRepository->getRecentUserOrders($userId, $limit);
    }

    /**
     * Get orders by status for a user.
     */
    public function getUserOrdersByStatus(int $userId, OrderStatus $status): Collection
    {
        return $this->orderRepository->getUserOrdersByStatus($userId, $status);
    }

    /**
     * Cancel an order.
     * Only pending and confirmed orders can be cancelled.
     */
    public function cancelOrder(int $orderId, int $userId, string $reason = ''): bool
    {
        $order = $this->getOrder($orderId, $userId);

        // Check if order can be cancelled
        if (! $order->canBeCancelled()) {
            throw new InvalidArgumentException(
                "Order cannot be cancelled. Current status: {$order->status->label()}"
            );
        }

        // Cancel the order
        $cancelled = $this->orderRepository->cancelOrder($orderId, $reason);

        if ($cancelled) {
            Log::info('Order cancelled by user', [
                'order_id' => $orderId,
                'user_id' => $userId,
                'reason' => $reason,
            ]);

            // TODO: Dispatch OrderCancelled event
            // TODO: Restore product stock
        }

        return $cancelled;
    }

    /**
     * Get order statistics for a user.
     */
    public function getUserOrderStats(int $userId): array
    {
        return $this->orderRepository->getUserOrderStats($userId);
    }

    /**
     * Check if user owns the order.
     */
    public function userOwnsOrder(int $userId, int $orderId): bool
    {
        return $this->orderRepository->userOwnsOrder($userId, $orderId);
    }

    /**
     * Get order by order number (for tracking).
     */
    public function getOrderByNumber(string $orderNumber, int $userId): ?Order
    {
        $order = $this->orderRepository->findByOrderNumber($orderNumber);

        if (! $order || $order->user_id !== $userId) {
            return null;
        }

        return $order;
    }

    /**
     * Track order status.
     */
    public function trackOrder(string $orderNumber, int $userId): ?array
    {
        $order = $this->getOrderByNumber($orderNumber, $userId);

        if (! $order) {
            return null;
        }

        return [
            'order_number' => $order->order_number,
            'status' => $order->status,
            'status_label' => $order->status->label(),
            'created_at' => $order->created_at,
            'items_count' => $order->items->count(),
            'total_amount' => $order->total_amount,
            'shipping_address' => $order->shipping_address,
        ];
    }

    // ========== ADMIN METHODS ==========

    /**
     * Get all orders (admin).
     */
    public function getAllOrders(int $perPage = 20): LengthAwarePaginator
    {
        return $this->orderRepository->getAllOrders($perPage);
    }

    /**
     * Get orders by status (admin).
     */
    public function getOrdersByStatus(OrderStatus $status, int $perPage = 20): LengthAwarePaginator
    {
        return $this->orderRepository->getOrdersByStatus($status, $perPage);
    }

    /**
     * Update order status (admin).
     */
    public function updateOrderStatus(int $orderId, OrderStatus $status, ?string $adminNotes = null): bool
    {
        $updated = $this->orderRepository->updateStatus($orderId, $status, $adminNotes);

        if ($updated) {
            Log::info('Order status updated', [
                'order_id' => $orderId,
                'new_status' => $status->value,
                'admin_notes' => $adminNotes,
            ]);

            // TODO: Dispatch OrderStatusUpdated event
            // TODO: Send notification to customer
        }

        return $updated;
    }

    /**
     * Get total revenue (admin).
     */
    public function getTotalRevenue(): float
    {
        return $this->orderRepository->getTotalRevenue();
    }

    /**
     * Get orders within date range (admin).
     */
    public function getOrdersBetweenDates(string $startDate, string $endDate): Collection
    {
        return $this->orderRepository->getOrdersBetweenDates($startDate, $endDate);
    }

    /**
     * Delete order (admin - soft delete).
     */
    public function deleteOrder(int $orderId): bool
    {
        $deleted = $this->orderRepository->delete($orderId);

        if ($deleted) {
            Log::warning('Order deleted', [
                'order_id' => $orderId,
            ]);
        }

        return $deleted;
    }
}
