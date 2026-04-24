<?php

declare(strict_types=1);

namespace App\Actions\Skus;

use App\Models\Sku;

class UpdateSkuQuantity
{
    public function handle(Sku $sku, int $quantity): void
    {
        $sku->update([
            'quantity' => $quantity,
        ]);
    }
}
