<?php

declare(strict_types=1);

namespace App\Shared\Enums;

enum VendorOrderStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Packed = 'packed';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Rejected = 'rejected';

    /**
     * Get a human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Accepted => 'Accepted',
            self::Packed => 'Packed',
            self::Shipped => 'Shipped',
            self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
            self::Rejected => 'Rejected',
        };
    }

    /**
     * Get color class for UI styling.
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Accepted => 'blue',
            self::Packed => 'indigo',
            self::Shipped => 'purple',
            self::Delivered => 'green',
            self::Cancelled => 'red',
            self::Rejected => 'gray',
        };
    }

    /**
     * Get badge color for Tailwind CSS styling.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-yellow-100 text-yellow-800',
            self::Accepted => 'bg-blue-100 text-blue-800',
            self::Packed => 'bg-indigo-100 text-indigo-800',
            self::Shipped => 'bg-purple-100 text-purple-800',
            self::Delivered => 'bg-green-100 text-green-800',
            self::Cancelled => 'bg-red-100 text-red-800',
            self::Rejected => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Check if vendor order can be cancelled by vendor.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this, [self::Pending, self::Accepted, self::Packed]);
    }

    /**
     * Check if the vendor order is in a final state.
     */
    public function isFinal(): bool
    {
        return in_array($this, [self::Delivered, self::Cancelled, self::Rejected]);
    }

    /**
     * Check if vendor can accept the order.
     */
    public function canBeAccepted(): bool
    {
        return $this === self::Pending;
    }

    /**
     * Check if vendor can mark as packed.
     */
    public function canBePacked(): bool
    {
        return $this === self::Accepted;
    }

    /**
     * Check if vendor can mark as shipped.
     */
    public function canBeShipped(): bool
    {
        return $this === self::Packed;
    }

    /**
     * Check if vendor can mark as delivered.
     */
    public function canBeDelivered(): bool
    {
        return $this === self::Shipped;
    }

    /**
     * Get next allowed status transitions.
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending => [self::Accepted, self::Rejected, self::Cancelled],
            self::Accepted => [self::Packed, self::Cancelled],
            self::Packed => [self::Shipped, self::Cancelled],
            self::Shipped => [self::Delivered],
            self::Delivered => [],
            self::Cancelled => [],
            self::Rejected => [],
        };
    }

    /**
     * Get all active statuses (non-final).
     */
    public static function activeStatuses(): array
    {
        return [
            self::Pending,
            self::Accepted,
            self::Packed,
            self::Shipped,
        ];
    }

    /**
     * Get all statuses as array for form selects.
     */
    public static function toArray(): array
    {
        return array_map(fn (self $status) => [
            'value' => $status->value,
            'label' => $status->label(),
            'color' => $status->color(),
        ], self::cases());
    }
}
