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
                Section::make()
                    ->columns()
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('code')
                            ->placeholder('-'),
                        TextEntry::make('user_id')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('full_name'),
                        TextEntry::make('email')
                            ->label('Email address'),
                        TextEntry::make('phone'),
                        TextEntry::make('address'),
                        TextEntry::make('delivery_instructions')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('gross_total')
                            ->numeric(),
                        TextEntry::make('discount')
                            ->numeric(),
                        TextEntry::make('delivery_charge')
                            ->numeric(),
                        TextEntry::make('tax')
                            ->numeric(),
                        TextEntry::make('net_total')
                            ->numeric(),
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
