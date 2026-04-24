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
            'product' => fn (array $attributes) => Sku::find($attributes['sku_id'])?->product?->title ?? fake()->words(3, true),
            'sku_code' => fn (array $attributes) => Sku::find($attributes['sku_id'])?->code ?? fake()->unique()->bothify('SKU-#####'),
            'properties' => fn (array $attributes) => Sku::find($attributes['sku_id'])?->specifications ?? ['Size' => 'L', 'Color' => 'Red'],
            'unit_price' => fn (array $attributes) => Sku::find($attributes['sku_id'])?->price ?? fake()->randomFloat(2, 10, 100),
            'quantity' => fake()->numberBetween(1, 5),
            'subtotal' => fn (array $attributes): int|float => $attributes['unit_price'] * $attributes['quantity'],
        ];
    }
}
