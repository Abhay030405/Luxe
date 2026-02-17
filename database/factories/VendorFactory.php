<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $businessName = fake()->company();

        return [
            'user_id' => User::factory(),
            'business_name' => $businessName,
            'slug' => Str::slug($businessName).'-'.fake()->unique()->numberBetween(1, 9999),
            'description' => fake()->paragraph(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'logo' => null,
            'banner' => null,
            'address_line1' => fake()->streetAddress(),
            'address_line2' => fake()->optional()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => 'USA',
            'business_type' => fake()->randomElement(['individual', 'company', 'partnership']),
            'tax_id' => fake()->optional()->numerify('##-#######'),
            'registration_number' => fake()->optional()->numerify('REG-######'),
            'status' => 'approved',
            'commission_rate' => fake()->randomFloat(2, 5, 20),
            'bank_name' => fake()->optional()->company().' Bank',
            'bank_account_number' => fake()->optional()->numerify('############'),
            'bank_account_holder' => fake()->optional()->name(),
            'bank_routing_number' => fake()->optional()->numerify('#########'),
            'social_links' => [
                'facebook' => fake()->optional()->url(),
                'twitter' => fake()->optional()->url(),
                'instagram' => fake()->optional()->url(),
            ],
            'meta_data' => null,
            'approved_by' => null,
            'approved_at' => now(),
            'rejection_reason' => null,
        ];
    }

    /**
     * Indicate that the vendor is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
        ]);
    }

    /**
     * Indicate that the vendor is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
        ]);
    }

    /**
     * Indicate that the vendor is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => fake()->sentence(),
        ]);
    }
}
