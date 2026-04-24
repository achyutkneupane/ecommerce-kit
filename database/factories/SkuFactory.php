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
        $faker = app(\Faker\Generator::class);

        return [
            'product_id' => Product::factory(),
            'code' => $faker->unique()->bothify('SKU-####-????'),
            'price' => $faker->randomFloat(2, 50, 2000), // $50 to $2000
            'quantity' => $faker->numberBetween(5, 50),
            'specifications' => function (array $attributes) use ($faker) {
                $product = Product::find($attributes['product_id']);
                $categoryName = $product?->category?->name ?? $faker->ecommerceCategory();

                return $faker->ecommerceVariantProperties($categoryName);
            },
        ];
    }
}
