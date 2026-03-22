<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Sku;

class SkuObserver
{
    public function creating(Sku $sku): void
    {
        $product = $sku->product()->first();
        $productCode = $product->code;

        $lastSku = Sku::query()->where('product_id', $sku->product_id)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSku) {
            $lastCodeNumber = (int) mb_substr($lastSku->code, mb_strrpos($lastSku->code, 'S') + 1);
            $newCodeNumber = $lastCodeNumber + 1;
        } else {
            $newCodeNumber = 1;
        }

        $sku->code = $productCode.'S'.mb_str_pad((string) $newCodeNumber, 3, '0', STR_PAD_LEFT);
    }
}
