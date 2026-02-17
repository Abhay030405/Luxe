<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Models;

use App\Models\User;
use App\Modules\Product\Models\Product;
use Database\Factories\VendorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_name',
        'slug',
        'description',
        'email',
        'phone',
        'logo',
        'banner',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'business_type',
        'tax_id',
        'registration_number',
        'status',
        'commission_rate',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'bank_routing_number',
        'social_links',
        'meta_data',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): VendorFactory
    {
        return VendorFactory::new();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'social_links' => 'array',
            'meta_data' => 'array',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns this vendor account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved this vendor.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all products for this vendor.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get active products for this vendor.
     */
    public function activeProducts(): HasMany
    {
        return $this->hasMany(Product::class)->where('status', 'active');
    }

    /**
     * Scope a query to only include approved vendors.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include pending vendors.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include suspended vendors.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Check if vendor is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if vendor is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if vendor is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if vendor is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get the vendor's full address as a string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get the vendor's logo URL or default.
     */
    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? asset('storage/'.$this->logo)
            : asset('images/default-vendor-logo.png');
    }

    /**
     * Get the vendor's banner URL or default.
     */
    public function getBannerUrlAttribute(): string
    {
        return $this->banner
            ? asset('storage/'.$this->banner)
            : asset('images/default-vendor-banner.png');
    }

    /**
     * Get the route to the vendor's store page.
     */
    public function getStoreUrlAttribute(): string
    {
        return route('vendor.store.show', $this->slug);
    }
}
