<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Product Title')
                    ->columnSpanFull()
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required(),
                Select::make('brand_id')
                    ->searchable()
                    ->preload()
                    ->relationship('brand', 'name'),
                KeyValue::make('specifications')
                    ->addable(false)
                    ->hiddenJs(<<<'JS'
                        $get('category_id') === null
                    JS)
                    ->columnSpanFull(),
            ]);
    }
}
