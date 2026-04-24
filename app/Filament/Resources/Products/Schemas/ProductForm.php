<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use App\Actions\Products\ApplyCategorySpecifications;
use App\Enums\LoyaltyMode;
use App\Models\Category;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Details')
                    ->description('Basic information about the product')
                    ->schema([
                        TextInput::make('title')
                            ->label('Product Title')
                            ->columnSpanFull()
                            ->required(),
                        Grid::make(2)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Category')
                                    ->options(
                                        fn () => Category::query()
                                            ->select('id', 'name', 'parent_id')
                                            ->with('parent:id,name')
                                            ->get()
                                            ->groupBy(fn ($category) => $category->parent->name ?? 'Parent Categories')
                                            ->map(fn ($group) => $group->pluck('name', 'id'))
                                            ->toArray()
                                    )
                                    ->afterStateUpdated(
                                        fn (Set $set, ?int $state, ApplyCategorySpecifications $applyCategorySpecifications) => $state ? $applyCategorySpecifications->handle($set, (int) $state) : null
                                    )
                                    ->searchable()
                                    ->live()
                                    ->required(),
                                Select::make('brand_id')
                                    ->searchable()
                                    ->preload()
                                    ->relationship('brand', 'name'),
                            ]),
                    ]),
                Section::make('Specifications')
                    ->schema([
                        KeyValue::make('specifications')
                            ->helperText("Only the common specifications which won't be different in SKUs")
                            ->keyLabel('Specification')
                            ->valueLabel('Value')
                            ->hiddenJs(<<<'JS'
                                $get('category_id') === null
                            JS)
                            ->columnSpanFull(),
                    ]),
                Section::make('Loyalty Program')
                    ->description('Override global loyalty settings for this product')
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
