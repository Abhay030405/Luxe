<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin || $this->role === 'admin';
    }

    /**
     * Check if user has vendor role.
     */
    public function hasVendorRole(): bool
    {
        return $this->role === 'vendor';
    }

    /**
     * Check if user is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Get the user's profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get all addresses for the user.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the user's default address.
     */
    public function defaultAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    /**
     * Get all cart items for the user.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(\App\Modules\Cart\Models\CartItem::class);
    }

    /**
     * Get all orders for the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(\App\Modules\Order\Models\Order::class);
    }

    /**
     * Get the vendor account for this user.
     */
    public function vendor(): HasOne
    {
        return $this->hasOne(\App\Modules\Vendor\Models\Vendor::class);
    }

    /**
     * Get the vendor application for this user.
     */
    public function vendorApplication(): HasOne
    {
        return $this->hasOne(\App\Modules\Vendor\Models\VendorApplication::class);
    }

    /**
     * Check if user is a vendor.
     */
    public function isVendor(): bool
    {
        return $this->vendor()->exists();
    }

    /**
     * Check if user has an approved vendor account.
     */
    public function isApprovedVendor(): bool
    {
        return $this->vendor()->where('status', 'approved')->exists();
    }

    /**
     * Check if user has a pending vendor application.
     */
    public function hasPendingVendorApplication(): bool
    {
        return $this->vendorApplication()->where('status', 'pending')->exists();
    }

    /**
     * Check if user has any vendor application.
     */
    public function hasVendorApplication(): bool
    {
        return $this->vendorApplication()->exists();
    }
}
