<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\Product\Models\Product;
use App\Modules\Wishlist\Models\WishlistItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Wishlist\Models\WishlistItem>
 */
class WishlistItemFactory extends Factory
{
    protected $model = WishlistItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
