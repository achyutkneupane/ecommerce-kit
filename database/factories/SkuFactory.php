<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sku;
use Faker\Generator;
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
        $generator = resolve(Generator::class);

        return [
            'product_id' => Product::factory(),
            'code' => $generator->unique()->bothify('SKU-####-????'),
            'price' => $generator->randomFloat(2, 50, 2000), // $50 to $2000
            'quantity' => $generator->numberBetween(5, 50),
            'specifications' => function (array $attributes) use ($generator) {
                $product = Product::query()->find($attributes['product_id']);
                $categoryName = $product?->category?->name ?? $generator->ecommerceCategory();

                return $generator->ecommerceVariantProperties($categoryName);
            },
        ];
    }
}
