<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use App\Services\OrderService;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Override;

class OrderProductsEntry extends RepeatableEntry
{
    public string $formFieldName = 'products';

    public string $grossTotalFieldName = 'gross_total';

    #[Override]
    protected function setUp(): void
    {
        parent::label('Products');
        parent::hiddenLabel();
        parent::placeholder('');
        parent::state(fn (Get $get, Set $set, OrderService $orderService): array => $this->resolveProductsState($get, $set, $orderService));
        parent::table([
            TableColumn::make('Product'),
            TableColumn::make('Unit Price'),
            TableColumn::make('Quantity'),
            TableColumn::make('Subtotal'),
        ]);
        parent::schema([
            TextEntry::make('name_entry')->label('Product'),
            TextEntry::make('unit_price')->label('Unit Price')->money(),
            TextEntry::make('quantity')->label('Quantity'),
            TextEntry::make('subtotal')->label('Subtotal')->money(),
        ]);
        parent::columnSpanFull();
    }

    #[Override]
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

    protected function resolveProductsState(Get $get, Set $set, OrderService $orderService): array
    {
        $result = $orderService->calculateOrderTotals($get($this->formFieldName) ?? []);

        $set($this->grossTotalFieldName, $result['grossTotal']);

        return $result['products'];
    }
}
