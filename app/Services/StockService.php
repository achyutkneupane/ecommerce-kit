<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Sku;

class StockService
{
    public function getAvailableQuantity(int $productId, ?int $skuId = null): int
    {
        return (int) cache()->remember(
            sprintf('product-%s-sku-%s-quantity', $productId, $skuId ?? 'base'),
            now()->addMinutes(5),
            function () use ($productId, $skuId): int {
                if ($skuId) {
                    return Sku::query()->find($skuId)?->quantity ?? 0;
                }

                return Product::query()->find($productId)?->quantity ?? 0;
            }
        );
    }
}
