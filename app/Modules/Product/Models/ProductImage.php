<?php

declare(strict_types=1);

namespace App\Modules\Product\Models;

use Database\Factories\ProductImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'is_primary',
        'sort_order',
    ];

    /**
     * Append accessors to array/JSON output.
     */
    protected $appends = ['image_url', 'url'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProductImageFactory
    {
        return ProductImageFactory::new();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the product that owns this image.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the full URL of the image.
     */
    public function getImageUrl(): string
    {
        // Check if the file exists, otherwise use a placeholder
        $fullPath = public_path('storage/'.$this->image_path);

        if (file_exists($fullPath)) {
            return asset('storage/'.$this->image_path);
        }

        // Use placeholder.com for demo images (640x640 size)
        $productId = $this->product_id ?? rand(1, 1000);

        return "https://via.placeholder.com/640x640/4F46E5/FFFFFF?text=Product+{$productId}";
    }

    /**
     * Accessor for image_url attribute.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->getImageUrl();
    }

    /**
     * Accessor for url attribute (alias for image_url).
     */
    public function getUrlAttribute(): string
    {
        return $this->getImageUrl();
    }
}
