<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('ORD-#####'),
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'delivery_instructions' => fake()->sentence(),
            'status' => OrderStatus::INITIATED,
            'gross_total' => fake()->numberBetween(1000, 10000),
            'discount' => 0,
            'delivery_charge' => 0,
            'tax' => 0,
            'net_total' => fn (array $attributes) => $attributes['gross_total'],
        ];
    }
}
