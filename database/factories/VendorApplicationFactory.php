<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Vendor\Models\VendorApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorApplication>
 */
class VendorApplicationFactory extends Factory
{
    protected $model = VendorApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'shop_name' => fake()->company(),
            'business_address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'pincode' => fake()->postcode(),
            'product_category' => fake()->randomElement(['Clothing', 'Electronics', 'Home & Garden', 'Sports', 'Books']),
            'estimated_products' => fake()->numberBetween(10, 500),
            'pickup_address' => fake()->optional()->streetAddress(),
            'gst_number' => fake()->optional()->numerify('##XXXXX####X#X#'),
            'id_proof' => null,
            'status' => 'pending',
            'admin_notes' => null,
            'rejection_reason' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'vendor_id' => null,
        ];
    }

    /**
     * Indicate that the application is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Indicate that the application is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'rejection_reason' => fake()->sentence(),
            'reviewed_at' => now(),
        ]);
    }
}
