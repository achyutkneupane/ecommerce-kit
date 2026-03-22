<?php

namespace App\Filament\Resources\Products\Resources\Skus\Schemas;

use App\Models\Sku;
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
                        TextEntry::make('code'),
                        TextEntry::make('price')
                            ->money(),
                        TextEntry::make('quantity')
                            ->numeric(),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (Sku $record): bool => $record->trashed()),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
