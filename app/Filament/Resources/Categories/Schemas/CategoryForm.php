<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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

            Repeater::make('children')
                ->label('Sub-categories')
                ->relationship('children')
                ->grid(3)
                ->schema(fn () => static::getSchema($depth + 1))
                ->hidden(fn () => $depth >= 1)
                ->collapsible()
                ->collapsed(false)
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components(fn () => CategoryForm::getSchema());
    }
}
