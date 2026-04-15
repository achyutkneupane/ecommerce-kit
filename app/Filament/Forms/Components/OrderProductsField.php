<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use App\Models\Product;
use App\Models\Sku;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class OrderProductsField extends Repeater
{
    protected function setUp(): void
    {
        parent::setUp();
        parent::relationship();
        parent::dehydrated();
        parent::hiddenLabel();
        parent::saveRelationshipsUsing(null);
        parent::cloneable();
        parent::minItems(1);
        parent::live();
        parent::schema([
            Select::make('product_id')
                ->label('Product')
                ->searchable()
                ->dehydrated(false)
                ->options(fn () => Product::query()->available()->pluck('title', 'id'))
                ->afterStateUpdated(function (Set $set): void {
                    $set('sku_id', null);
                    $set('quantity', null);
                })
                ->preload()
                ->required()
                ->live(),
            Select::make('sku_id')
                ->label('SKU')
                ->searchable()
                ->preload()
                ->options(function (Get $get) {
                    $productId = $get('product_id');

                    if (! $productId) {
                        return [];
                    }

                    return Product::find($productId)?->skus()
                        ->where('quantity', '>', 0)
                        ->select('specifications', 'price', 'id')
                        ->get()
                        ->mapWithKeys(function (Sku $sku) {
                            $specs = collect($sku->specifications)
                                ->map(fn ($value, $key) => sprintf('%s: %s', ucfirst($key), $value))
                                ->implode(', ');

                            return [$sku->id => sprintf('%s (%s)', $specs, $sku->price)];
                        })->toArray() ?? [];
                })
                ->required()
                ->live(),
            TextInput::make('quantity')
                ->label('Quantity')
                ->maxValue(fn (Get $get): int => self::getAvailableQuantity($get))
                ->helperText(fn (Get $get): string => 'Available: '.self::getAvailableQuantity($get))
                ->numeric()
                ->required()
                ->live(),
        ]);

        parent::table([
            TableColumn::make('Product'),
            TableColumn::make('SKU'),
            TableColumn::make('Quantity'),
        ]);
    }

    public static function make(?string $name = 'products'): static
    {
        return parent::make($name);
    }

    private static function getAvailableQuantity(Get $get): int
    {
        $productId = $get('product_id');
        $skuId = $get('sku_id');

        if (! $productId) {
            return 0;
        }

        return cache()->remember(
            sprintf('product-%s-sku-%s-quantity', $productId, $skuId ?? 'base'),
            now()->addMinutes(5),
            function () use ($productId, $skuId): int {
                if ($skuId) {
                    return Sku::find($skuId)?->quantity ?? 0;
                }

                return Product::find($productId)?->quantity ?? 0;
            }
        );
    }
}
