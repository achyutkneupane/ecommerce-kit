<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use App\Models\Product;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class OrderProductsEntry extends RepeatableEntry
{
    public string $formFieldName = 'products';

    public string $grossTotalFieldName = 'gross_total';

    protected function setUp(): void
    {
        parent::label('Products');
        parent::hiddenLabel();
        parent::placeholder('');
        parent::state(fn (Get $get, Set $set): array => $this->resolveProductsState($get, $set));
        parent::table([
            RepeatableEntry\TableColumn::make('Product'),
            RepeatableEntry\TableColumn::make('Unit Price'),
            RepeatableEntry\TableColumn::make('Quantity'),
            RepeatableEntry\TableColumn::make('Subtotal'),
        ]);
        parent::schema([
            TextEntry::make('name_entry')->label('Product'),
            TextEntry::make('unit_price')->label('Unit Price')->money(),
            TextEntry::make('quantity')->label('Quantity'),
            TextEntry::make('subtotal')->label('Subtotal')->money(),
        ]);
        parent::columnSpanFull();
    }

    public static function make(?string $name = 'products'): static
    {
        return parent::make($name);
    }

    public function formFieldName(string $name): static
    {
        $this->formFieldName = $name;

        return $this;
    }

    public function grossTotalFieldName(string $name): static
    {
        $this->grossTotalFieldName = $name;

        return $this;
    }

    protected function resolveProductsState(Get $get, Set $set): array
    {
        $productsState = collect($get($this->formFieldName) ?? [])
            ->filter(fn ($item) => ! empty($item['product_id']) && ! empty($item['sku_id']) && ! empty($item['quantity']));

        if ($productsState->isEmpty()) {
            $set($this->grossTotalFieldName, 0);

            return [];
        }

        $productIds = $productsState->pluck('product_id')->unique();
        $loadedProducts = Product::with('skus')->whereIn('id', $productIds)->get()->keyBy('id');

        $grossTotal = 0;
        $repeatableProducts = [];

        foreach ($productsState as $productLine) {
            $product = $loadedProducts->get($productLine['product_id']);
            $sku = $product?->skus->firstWhere('id', $productLine['sku_id']);

            if (! $product || ! $sku) {
                continue;
            }

            $specs = collect($sku->specifications)
                ->map(fn ($value, $key) => sprintf('%s: %s', ucfirst($key), $value))
                ->implode(', ');

            $unitPrice = $sku->price ?? 0;
            $quantity = (int) $productLine['quantity'];
            $subtotal = $unitPrice * $quantity;

            $grossTotal += $subtotal;

            $repeatableProducts[] = [
                'name_entry' => sprintf('%s (%s)', $product->title, $specs),
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        $set($this->grossTotalFieldName, $grossTotal);

        return $repeatableProducts;
    }
}
