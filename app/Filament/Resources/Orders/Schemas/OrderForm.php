<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Filament\Components\SearchCustomerAction;
use App\Filament\Forms\Components\OrderProductsField;
use App\Filament\Infolists\Components\OrderProductsEntry;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Customer')
                    ->columns()
                    ->collapsible()
                    ->components([
                        TextInput::make('name')
                            ->label('Customer Name')
                            ->live()
                            ->prefixAction(SearchCustomerAction::make())
                            ->required(),
                        TextInput::make('email')
                            ->label('Customer Email')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Customer Phone')
                            ->tel()
                            ->required(),
                        TextInput::make('address')
                            ->label('Customer Address')
                            ->required(),
                        Textarea::make('delivery_instructions')
                            ->label('Delivery Instructions')
                            ->columnSpanFull(),
                    ]),
                Section::make('Products')
                    ->columns(1)
                    ->collapsible()
                    ->components([
                        OrderProductsField::make('items'),
                    ]),

                Section::make('Pricing')
                    ->columns()
                    ->collapsible()
                    ->components([
                        OrderProductsEntry::make('products_entry')
                            ->formFieldName('items')
                            ->grossTotalFieldName('gross_total')
                            ->label('Products'),
                        TextEntry::make('gross_total')
                            ->label('Gross Total')
                            ->inlineLabel()
                            ->money()
                            ->badge()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
