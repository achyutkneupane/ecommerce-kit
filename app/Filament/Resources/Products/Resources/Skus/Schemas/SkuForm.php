<?php

namespace App\Filament\Resources\Products\Resources\Skus\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SkuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('price')
                    ->required()
                    ->columnSpanFull()
                    ->numeric(),
                KeyValue::make('specifications')
                    ->helperText('Don\'t repeat the common specifications which are already defined in the product')
                    ->keyLabel('Specification')
                    ->valueLabel('Value')
                    ->columnSpanFull(),
            ]);
    }
}
