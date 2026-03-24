<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLog extends MediaModel
{
    /** @return BelongsTo<Order> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
        ];
    }
}
