<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Product;
use App\Models\Sku;
use Illuminate\Support\Facades\DB;

class SkuObserver
{
    public function creating(Sku $sku): void
    {
        DB::transaction(function () use ($sku): void {
            $product = Product::query()->lockForUpdate()->find($sku->product_id);

            $sku->code = $product->code.'S'.mb_str_pad((string) $product->sku_sequence, 3, '0', STR_PAD_LEFT);

            $product->increment('sku_sequence');
        });
    }
}
