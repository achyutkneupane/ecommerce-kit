<?php

declare(strict_types=1);

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
                    ->helperText("Don't repeat the common specifications which are already defined in the product")
                    ->keyLabel('Specification')
                    ->valueLabel('Value')
                    ->columnSpanFull(),
                \Filament\Schemas\Components\Section::make('Loyalty Program')
                    ->description('Override product loyalty settings for this SKU')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                \Filament\Forms\Components\Select::make('loyalty_mode')
                                    ->options(\App\Enums\LoyaltyMode::class)
                                    ->nullable()
                                    ->live(),
                                TextInput::make('loyalty_amount')
                                    ->numeric()
                                    ->nullable()
                                    ->visible(fn (callable $get) => $get('loyalty_mode') !== null)
                                    ->label(fn (callable $get) => $get('loyalty_mode') === \App\Enums\LoyaltyMode::Percentage->value ? 'Percentage (%)' : 'Points per Unit'),
                            ]),
                    ]),
            ]);
    }
}
