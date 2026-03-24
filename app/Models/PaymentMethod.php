<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use App\Enums\PaymentMethodType;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends MediaModel
{
    /** @return HasMany<Payment> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    protected function casts(): array
    {
        return [
            'type' => PaymentMethodType::class,
            'settings' => 'array',
        ];
    }
}
