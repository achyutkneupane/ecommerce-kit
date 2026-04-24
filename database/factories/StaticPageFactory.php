<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PageType;
use App\Models\StaticPage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<StaticPage>
 */
class StaticPageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'name' => null,
            'type' => PageType::ContentPage,
            'tags' => fake()->words(3),
        ];
    }
}
