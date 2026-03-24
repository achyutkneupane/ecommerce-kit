<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends MediaModel
{
    /** @return BelongsTo<Order> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<Sku> */
    public function sku(): BelongsTo
    {
        return $this->belongsTo(Sku::class);
    }

    public function unitPrice(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int|float => $value / 100,
            set: fn (float|int $value): int => (int) ($value * 100),
        );
    }

    public function subtotal(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int|float => $value / 100,
            set: fn (float|int $value): int => (int) ($value * 100),
        );
    }

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }
}
