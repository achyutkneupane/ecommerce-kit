<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sku>
 */
class SkuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'code' => fake()->unique()->bothify('SKU-####-????'),
            'price' => fake()->numberBetween(1000, 100000), // in cents
            'quantity' => fake()->numberBetween(0, 100),
            'specifications' => [],
        ];
    }
}
