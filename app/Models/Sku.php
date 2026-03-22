<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use App\Observers\SkuObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property int $product_id
 * @property string $code
 * @property int|float $price
 * @property int $quantity
 * @property array<array-key, mixed>|null $specifications
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Product|null $product
 *
 * @method static Builder<static>|Sku newModelQuery()
 * @method static Builder<static>|Sku newQuery()
 * @method static Builder<static>|Sku onlyTrashed()
 * @method static Builder<static>|Sku query()
 * @method static Builder<static>|Sku whereCode($value)
 * @method static Builder<static>|Sku whereCreatedAt($value)
 * @method static Builder<static>|Sku whereDeletedAt($value)
 * @method static Builder<static>|Sku whereId($value)
 * @method static Builder<static>|Sku wherePrice($value)
 * @method static Builder<static>|Sku whereProductId($value)
 * @method static Builder<static>|Sku whereQuantity($value)
 * @method static Builder<static>|Sku whereSpecifications($value)
 * @method static Builder<static>|Sku whereUpdatedAt($value)
 * @method static Builder<static>|Sku withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Sku withoutTrashed()
 *
 * @mixin \Eloquent
 */
#[ObservedBy(SkuObserver::class)]
class Sku extends MediaModel
{
    use SoftDeletes;

    /** @return BelongsTo<Product> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function casts(): array
    {
        return [
            'specifications' => 'array',
        ];
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int|float => $value / 100,
            set: fn (float|int $value): int => (int) ($value * 100),
        );
    }
}
