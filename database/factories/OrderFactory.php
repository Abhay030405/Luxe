<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Modules\Order\Models\Order;
use App\Shared\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Order\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 50, 500);
        $tax = $subtotal * 0.0; // No tax by default
        $shippingFee = 0.0; // Free shipping by default
        $totalAmount = $subtotal + $tax + $shippingFee;

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.date('Y').'-'.str_pad((string) $this->faker->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'status' => OrderStatus::Pending,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_fee' => $shippingFee,
            'total_amount' => $totalAmount,
            'address_snapshot' => [
                'full_name' => $this->faker->name(),
                'phone' => $this->faker->phoneNumber(),
                'address_line_1' => $this->faker->streetAddress(),
                'address_line_2' => $this->faker->optional()->secondaryAddress(),
                'city' => $this->faker->city(),
                'state' => $this->faker->state(),
                'postal_code' => $this->faker->postcode(),
                'country' => 'United States',
                'address_type' => $this->faker->randomElement(['home', 'office']),
            ],
            'customer_notes' => $this->faker->optional(0.3)->sentence(),
            'admin_notes' => null,
        ];
    }

    /**
     * Indicate that the order is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Confirmed,
        ]);
    }

    /**
     * Indicate that the order is processing.
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Processing,
        ]);
    }

    /**
     * Indicate that the order is shipped.
     */
    public function shipped(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Shipped,
        ]);
    }

    /**
     * Indicate that the order is delivered.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Delivered,
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderStatus::Cancelled,
        ]);
    }

    /**
     * Indicate that the order has tax.
     */
    public function withTax(float $rate = 0.1): static
    {
        return $this->state(function (array $attributes) use ($rate) {
            $tax = $attributes['subtotal'] * $rate;
            $totalAmount = $attributes['subtotal'] + $tax + $attributes['shipping_fee'];

            return [
                'tax' => $tax,
                'total_amount' => $totalAmount,
            ];
        });
    }

    /**
     * Indicate that the order has shipping fee.
     */
    public function withShipping(float $fee = 15.00): static
    {
        return $this->state(function (array $attributes) use ($fee) {
            $totalAmount = $attributes['subtotal'] + $attributes['tax'] + $fee;

            return [
                'shipping_fee' => $fee,
                'total_amount' => $totalAmount,
            ];
        });
    }
}
