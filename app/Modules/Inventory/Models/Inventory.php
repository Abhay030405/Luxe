<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Models;

use App\Modules\Product\Models\Product;
use Database\Factories\Modules\InventoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'quantity_available',
        'quantity_reserved',
        'low_stock_threshold',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity_available' => 'integer',
            'quantity_reserved' => 'integer',
            'low_stock_threshold' => 'integer',
        ];
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): InventoryFactory
    {
        return InventoryFactory::new();
    }

    /**
     * Get the product that owns this inventory.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get total stock (available + reserved).
     */
    public function getTotalStockAttribute(): int
    {
        return $this->quantity_available + $this->quantity_reserved;
    }

    /**
     * Check if stock is low based on threshold.
     */
    public function isLowStock(): bool
    {
        return $this->quantity_available <= $this->low_stock_threshold;
    }

    /**
     * Check if product is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity_available <= 0;
    }

    /**
     * Check if quantity is available for purchase.
     */
    public function hasAvailableStock(int $quantity): bool
    {
        return $this->quantity_available >= $quantity;
    }

    /**
     * Scope to get low stock items.
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity_available <= low_stock_threshold');
    }

    /**
     * Scope to get out of stock items.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity_available', '<=', 0);
    }

    /**
     * Scope to get items with available stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity_available', '>', 0);
    }
}
