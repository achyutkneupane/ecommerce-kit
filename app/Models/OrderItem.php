<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property int $order_id
 * @property int|null $sku_id
 * @property string $product
 * @property string $sku_code
 * @property array<array-key, mixed>|null $properties
 * @property int|float $unit_price
 * @property int $quantity
 * @property int|float $subtotal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Order $order
 * @property-read Sku|null $sku
 *
 * @method static Builder<static>|OrderItem newModelQuery()
 * @method static Builder<static>|OrderItem newQuery()
 * @method static Builder<static>|OrderItem query()
 * @method static Builder<static>|OrderItem whereCreatedAt($value)
 * @method static Builder<static>|OrderItem whereId($value)
 * @method static Builder<static>|OrderItem whereOrderId($value)
 * @method static Builder<static>|OrderItem whereProduct($value)
 * @method static Builder<static>|OrderItem whereProperties($value)
 * @method static Builder<static>|OrderItem whereQuantity($value)
 * @method static Builder<static>|OrderItem whereSkuCode($value)
 * @method static Builder<static>|OrderItem whereSkuId($value)
 * @method static Builder<static>|OrderItem whereSubtotal($value)
 * @method static Builder<static>|OrderItem whereUnitPrice($value)
 * @method static Builder<static>|OrderItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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

    protected function unitPrice(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int|float => $value / 100,
            set: fn (float|int $value): int => (int) ($value * 100),
        );
    }

    protected function subtotal(): Attribute
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
