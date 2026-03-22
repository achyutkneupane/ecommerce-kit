<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
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
                        TextEntry::make('title'),
                        TextEntry::make('category.name')
                            ->label('Category'),
                        TextEntry::make('brand.name')
                            ->label('Brand')
                            ->placeholder('-'),
                        KeyValueEntry::make('specifications')
                            ->label('Specifications')
                            ->columnSpanFull()
                            ->keyLabel('Specification')
                            ->valueLabel('Value')
                            ->placeholder('-')
                            ->hidden(fn (?Product $product): bool => empty($product->specifications)),
                    ]),
            ]);
    }
}
