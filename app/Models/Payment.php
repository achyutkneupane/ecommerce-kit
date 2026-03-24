<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends MediaModel
{
    /** @return BelongsTo<Order> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<PaymentMethod> */
    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}
