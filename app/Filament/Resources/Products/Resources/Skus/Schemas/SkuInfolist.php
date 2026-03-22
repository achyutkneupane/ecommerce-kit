<?php

namespace App\Filament\Resources\Products\Resources\Skus\Schemas;

use App\Models\Sku;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SkuInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns()
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('price')
                            ->money(),
                        TextEntry::make('quantity')
                            ->badge()
                            ->numeric(),
                        KeyValueEntry::make('specifications')
                            ->label('Specifications')
                            ->columnSpanFull()
                            ->keyLabel('Specification')
                            ->valueLabel('Value')
                            ->placeholder('-')
                            ->hidden(fn (?Sku $record) => empty($record->specifications)),
                    ]),
            ]);
    }
}
