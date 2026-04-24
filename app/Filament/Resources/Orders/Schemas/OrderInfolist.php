<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Details')
                    ->columns()
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('full_name'),
                        TextEntry::make('email')
                            ->label('Email address'),
                        TextEntry::make('phone'),
                        TextEntry::make('address'),
                        TextEntry::make('delivery_instructions')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Section::make('Pricing')
                    ->columns(1)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('gross_total')
                            ->inlineLabel()
                            ->badge()
                            ->hidden(fn ($state): bool => $state === 0)
                            ->money(),
                        TextEntry::make('discount')
                            ->inlineLabel()
                            ->badge()
                            ->hidden(fn ($state): bool => $state === 0)
                            ->money(),
                        TextEntry::make('delivery_charge')
                            ->inlineLabel()
                            ->badge()
                            ->hidden(fn ($state): bool => $state === 0)
                            ->money(),
                        TextEntry::make('tax')
                            ->inlineLabel()
                            ->badge()
                            ->hidden(fn ($state): bool => $state === 0)
                            ->money(),
                        TextEntry::make('net_total')
                            ->inlineLabel()
                            ->badge()
                            ->hidden(fn ($state): bool => $state === 0)
                            ->money(),
                    ]),
            ]);
    }
}
