<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $faker = app(\Faker\Generator::class);
        $categoryName = $faker->ecommerceCategory();
        $title = $faker->ecommerceProductTitle($categoryName);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'category_id' => Category::factory()->state(['name' => $categoryName]),
            'brand_id' => Brand::factory()->state(['name' => $faker->ecommerceBrand($categoryName)]),
            'specifications' => $faker->ecommerceSpecifications($categoryName),
            'sku_sequence' => 0,
        ];
    }
}
