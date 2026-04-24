<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Resources\Skus\Schemas;

use App\Enums\LoyaltyMode;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
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
                Section::make('Loyalty Program')
                    ->description('Override product loyalty settings for this SKU')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('loyalty_mode')
                                    ->options(LoyaltyMode::class)
                                    ->nullable()
                                    ->live(),
                                TextInput::make('loyalty_amount')
                                    ->numeric()
                                    ->nullable()
                                    ->visible(fn (callable $get): bool => $get('loyalty_mode') !== null)
                                    ->label(fn (callable $get): string => $get('loyalty_mode') === LoyaltyMode::Percentage->value ? 'Percentage (%)' : 'Points per Unit'),
                            ]),
                    ]),
            ]);
    }
}
