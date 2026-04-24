<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'sku_id' => Sku::factory(),
            'product' => fake()->words(3, true),
            'sku_code' => fake()->unique()->bothify('SKU-#####'),
            'properties' => ['Size' => 'L', 'Color' => 'Red'],
            'unit_price' => fake()->numberBetween(100, 1000),
            'quantity' => fake()->numberBetween(1, 5),
            'subtotal' => fn (array $attributes): int|float => $attributes['unit_price'] * $attributes['quantity'],
        ];
    }
}
