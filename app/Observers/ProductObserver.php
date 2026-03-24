<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Product;
use App\Settings\SiteSettings;

class ProductObserver
{
    public function created(Product $product): void
    {
        $siteSettings = resolve(SiteSettings::class);
        $prefix = $siteSettings->store_prefix ?? 'EK';
        $prefix .= 'P';

        $product->code = $prefix.mb_str_pad((string) $product->id, 5, '0', STR_PAD_LEFT);
        $product->saveQuietly();
    }
}
