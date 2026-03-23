<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Resources\Skus\Tables;

use App\Models\Sku;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
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
                            ->formatStateUsing(fn (Sku $record) => $record->quantity)
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'quantity' => $data['quantity'],
                        ]);
                    }),
            ]);
    }
}
