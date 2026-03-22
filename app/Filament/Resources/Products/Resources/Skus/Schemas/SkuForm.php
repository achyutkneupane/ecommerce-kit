<?php

namespace App\Filament\Resources\Products\Resources\Skus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SkuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('specifications'),
            ]);
    }
}
