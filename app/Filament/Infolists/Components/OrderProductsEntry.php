<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use App\Models\Product;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Collection;

class OrderProductsEntry extends RepeatableEntry
{
    public string $formFieldName = 'products';
    public string $grossTotalFieldName = 'gross_total';

    protected function setUp(): void
    {
        parent::label('Products');
        parent::hiddenLabel();
        parent::placeholder('');
        parent::state(fn (Get $get, Set $set): array => self::getProductsEntry($get, $set));
        parent::table([
            RepeatableEntry\TableColumn::make('Product'),
            RepeatableEntry\TableColumn::make('Unit Price'),
            RepeatableEntry\TableColumn::make('Quantity'),
            RepeatableEntry\TableColumn::make('Subtotal'),
        ]);
        parent::schema([
            TextEntry::make('name_entry')
                ->label('Product'),
            TextEntry::make('unit_price')
                ->label('Unit Price')
                ->money(),
            TextEntry::make('quantity')
                ->label('Quantity'),
            TextEntry::make('subtotal')
                ->label('Subtotal')
                ->money(),
        ]);
        parent::columnSpanFull();
    }

    public static function make(?string $name = 'products'): static
    {
        return parent::make($name);
    }

    public function getProductsEntry(Get $get, Set $set): array
    {
        /** @var Collection<Product> $products */
        $products = $get($this->formFieldName) ?? [];

        $grossTotal = 0;

        $repeatableProducts = [];

        foreach ($products as $productLine) {
            if (is_null($productLine['product_id']) || is_null($productLine['sku_id']) || is_null($productLine['quantity'])) {
                continue;
            }

            $product = Product::with('skus')->find($productLine['product_id']);
            $sku = $product->skus->find($productLine['sku_id']);

            $productTitle = $product->title;
            if ($sku) {
                $specs = collect($sku->specifications)->map(fn ($value, $key) => sprintf('%s: %s', ucfirst($key), $value))->implode(', ');
                $productTitle .= " ({$specs})";
            }

            $unitPrice = $sku->price ?? 0;
            $quantity = $productLine['quantity'] ?? 0;

            $grossTotal += $unitPrice * $quantity;

            $repeatableProducts[] = [
                'name_entry' => $productTitle,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $unitPrice * $quantity,
            ];
        }

        $set($this->grossTotalFieldName, $grossTotal);

        return $repeatableProducts;
    }

    public function formFieldName(string $name): self
    {
        $this->formFieldName = $name;

        return $this;
    }

    public function grossTotalFieldName(string $name): self
    {
        $this->grossTotalFieldName = $name;

        return $this;
    }
}
