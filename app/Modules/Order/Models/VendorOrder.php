<?php

declare(strict_types=1);

namespace App\Modules\Order\Models;

use App\Modules\Vendor\Models\Vendor;
use App\Shared\Enums\VendorOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorOrder extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_id',
        'vendor_id',
        'vendor_order_number',
        'status',
        'subtotal',
        'commission_rate',
        'commission_amount',
        'vendor_earnings',
        'tracking_number',
        'shipping_carrier',
        'accepted_at',
        'packed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'vendor_notes',
        'cancellation_reason',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => VendorOrderStatus::class,
            'subtotal' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'vendor_earnings' => 'decimal:2',
            'accepted_at' => 'datetime',
            'packed_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the parent customer order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the vendor that owns this order.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the items for this vendor order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope to get vendor orders for a specific vendor.
     */
    public function scopeForVendor($query, int $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeWithStatus($query, VendorOrderStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get recent vendor orders.
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->latest('created_at')->limit($limit);
    }

    /**
     * Scope to get pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('status', VendorOrderStatus::Pending);
    }

    /**
     * Scope to get active orders (non-final statuses).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', VendorOrderStatus::activeStatuses());
    }

    /**
     * Check if the vendor order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return $this->status->canBeCancelled();
    }

    /**
     * Check if the vendor order is in a final state.
     */
    public function isFinal(): bool
    {
        return $this->status->isFinal();
    }

    /**
     * Check if vendor can accept the order.
     */
    public function canBeAccepted(): bool
    {
        return $this->status->canBeAccepted();
    }

    /**
     * Check if vendor can mark as packed.
     */
    public function canBePacked(): bool
    {
        return $this->status->canBePacked();
    }

    /**
     * Check if vendor can mark as shipped.
     */
    public function canBeShipped(): bool
    {
        return $this->status->canBeShipped();
    }

    /**
     * Check if vendor can mark as delivered.
     */
    public function canBeDelivered(): bool
    {
        return $this->status->canBeDelivered();
    }

    /**
     * Get formatted vendor order number for display.
     */
    public function getFormattedVendorOrderNumberAttribute(): string
    {
        return strtoupper($this->vendor_order_number);
    }

    /**
     * Get formatted subtotal with currency symbol.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return '₹'.number_format((float) $this->subtotal, 2);
    }

    /**
     * Get formatted commission amount with currency symbol.
     */
    public function getFormattedCommissionAttribute(): string
    {
        return '₹'.number_format((float) $this->commission_amount, 2);
    }

    /**
     * Get formatted vendor earnings with currency symbol.
     */
    public function getFormattedEarningsAttribute(): string
    {
        return '₹'.number_format((float) $this->vendor_earnings, 2);
    }

    /**
     * Calculate total items count in the vendor order.
     */
    public function getTotalItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    /**
     * Get the shipping address from parent order's snapshot.
     */
    public function getShippingAddressAttribute(): ?array
    {
        return $this->order?->address_snapshot;
    }

    /**
     * Get customer information from parent order.
     */
    public function getCustomerAttribute()
    {
        return $this->order?->user;
    }
}
