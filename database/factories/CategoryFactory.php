<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = resolve(Generator::class)->ecommerceCategory();

        return [
            'name' => $name,
            'parent_id' => null,
            'specifications' => [],
        ];
    }
}
