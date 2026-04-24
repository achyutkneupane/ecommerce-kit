<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;

class OrderService
{
    /**
     * @param  array<array-key, mixed>  $productsState
     * @return array{products: array<array-key, mixed>, grossTotal: int}
     */
    public function calculateOrderTotals(array $productsState): array
    {
        $products = collect($productsState)
            ->filter(fn ($item): bool => ! empty($item['product_id']) && ! empty($item['sku_id']) && ! empty($item['quantity']));

        if ($products->isEmpty()) {
            return [
                'products' => [],
                'grossTotal' => 0,
            ];
        }

        $productIds = $products->pluck('product_id')->unique();
        $loadedProducts = Product::query()->with('skus')->whereIn('id', $productIds)->get()->keyBy('id');

        $grossTotal = 0;
        $repeatableProducts = [];

        foreach ($products as $productLine) {
            $product = $loadedProducts->get((int) $productLine['product_id']);
            $sku = $product?->skus->firstWhere('id', (int) $productLine['sku_id']);

            if (! $product || ! $sku) {
                continue;
            }

            $specs = collect($sku->specifications)
                ->map(fn ($value, $key): string => sprintf('%s: %s', ucfirst((string) $key), $value))
                ->implode(', ');

            $unitPrice = (int) ($sku->price ?? 0);
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

        return [
            'products' => $repeatableProducts,
            'grossTotal' => $grossTotal,
        ];
    }
}
