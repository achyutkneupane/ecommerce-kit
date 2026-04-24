<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Resources\Skus\Tables;

use App\Actions\Skus\UpdateSkuQuantity;
use App\Models\Sku;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SkusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->badge()
                    ->sortable(),
                TextColumn::make('specifications')
                    ->badge(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('update-quantity')
                    ->label('Update Quantity')
                    ->icon(Heroicon::NumberedList)
                    ->schema([
                        TextInput::make('quantity')
                            ->numeric()
                            ->formatStateUsing(fn (Sku $sku) => $sku->quantity)
                            ->required(),
                    ])
                    ->action(fn (Sku $sku, array $data, UpdateSkuQuantity $updateSkuQuantity) => $updateSkuQuantity->handle($sku, (int) $data['quantity'])),
            ]);
    }
}
