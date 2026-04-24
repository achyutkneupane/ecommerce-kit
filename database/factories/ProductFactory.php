<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $generator = resolve(Generator::class);
        $categoryName = $generator->ecommerceCategory();
        $title = $generator->ecommerceProductTitle($categoryName);

        return [
            'title' => $title,
            'category_id' => Category::factory()->state(['name' => $categoryName]),
            'brand_id' => Brand::factory()->state(['name' => $generator->ecommerceBrand($categoryName)]),
            'specifications' => $generator->ecommerceSpecifications($categoryName),
            'sku_sequence' => 0,
        ];
    }
}
