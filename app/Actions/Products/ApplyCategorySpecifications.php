<?php

declare(strict_types=1);

namespace App\Actions\Products;

use App\Models\Category;
use Filament\Schemas\Components\Utilities\Set;

class ApplyCategorySpecifications
{
    public function handle(Set $set, int $categoryId): void
    {
        $category = Category::query()->find($categoryId);

        if ($category && $category->specifications) {
            $mapped = array_map(
                fn ($specification): array => [
                    'key' => $specification,
                    'value' => '',
                ],
                (array) $category->specifications
            );

            $set('specifications', $mapped);
        }
    }
}
