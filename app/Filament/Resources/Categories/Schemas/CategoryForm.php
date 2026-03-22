<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function getSchema(int $depth = 0): array
    {
        if ($depth > 1) {
            return [];
        }

        return [
            TextInput::make('name')
                ->required()
                ->lazy(),

            TagsInput::make('specifications')
                ->label('Specifications')
                ->visible(fn (): true => $depth <= 1)
                ->columnSpanFull(),

            Repeater::make('children')
                ->label('Sub-categories')
                ->relationship('children')
                ->grid(3)
                ->schema(fn (): array => static::getSchema($depth + 1))
                ->hidden(fn (?Category $category): bool => $depth >= 1)
                ->visible(fn (?Category $category): bool => $category?->parent_id === null)
                ->collapsible()
                ->collapsed(false),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components(fn (): array => CategoryForm::getSchema());
    }
}
