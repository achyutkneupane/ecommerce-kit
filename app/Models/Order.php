<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends MediaModel
{
    /** @return BelongsTo<User> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<OrderItem> */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** @return HasMany<Payment> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /** @return HasMany<OrderLog> */
    public function logs(): HasMany
    {
        return $this->hasMany(OrderLog::class);
    }

    public function grossTotal(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int|float => $value / 100,
            set: fn (float|int $value): int => (int) ($value * 100),
        );
    }

    public function discount(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int|float => $value / 100,
            set: fn (float|int $value): int => (int) ($value * 100),
        );
    }

    public function deliveryCharge(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int|float => $value / 100,
            set: fn (float|int $value): int => (int) ($value * 100),
        );
    }

    public function tax(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int|float => $value / 100,
            set: fn (float|int $value): int => (int) ($value * 100),
        );
    }

    public function netTotal(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int|float => $value / 100,
            set: fn (float|int $value): int => (int) ($value * 100),
        );
    }

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
        ];
    }
}
