<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Product;
use App\Settings\SiteSettings;

class ProductObserver
{
    public function creating(Product $product): void
    {
        $siteSettings = resolve(SiteSettings::class);
        $prefix = $siteSettings->store_prefix ?? 'EK';
        $prefix .= 'P';

        $lastProduct = Product::query()->withoutGlobalScopes()->orderBy('id', 'desc')->first();

        $lastProductCode = $lastProduct?->code;
        $lastProductNumber = $lastProductCode ? (int) str_replace($prefix, '', $lastProductCode) : 0;

        $product->code = $prefix.mb_str_pad((string) ($lastProductNumber + 1), 5, '0', STR_PAD_LEFT);
    }
}
