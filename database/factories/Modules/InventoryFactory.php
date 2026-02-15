<?php

declare(strict_types=1);

namespace Database\Factories\Modules;

use App\Modules\Inventory\Models\Inventory;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inventory>
 */
class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'quantity_available' => $this->faker->numberBetween(0, 100),
            'quantity_reserved' => 0,
            'low_stock_threshold' => 10,
        ];
    }

    /**
     * State for products with low stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity_available' => $this->faker->numberBetween(1, 9),
            'low_stock_threshold' => 10,
        ]);
    }

    /**
     * State for out of stock products.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity_available' => 0,
            'quantity_reserved' => 0,
        ]);
    }

    /**
     * State for products with reserved stock.
     */
    public function withReservedStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity_available' => $this->faker->numberBetween(20, 50),
            'quantity_reserved' => $this->faker->numberBetween(5, 15),
        ]);
    }

    /**
     * State for high stock products.
     */
    public function highStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity_available' => $this->faker->numberBetween(100, 500),
            'quantity_reserved' => 0,
            'low_stock_threshold' => 20,
        ]);
    }
}
