<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property int|float $price
 *
 * @method static Builder<static>|Sku newModelQuery()
 * @method static Builder<static>|Sku newQuery()
 * @method static Builder<static>|Sku onlyTrashed()
 * @method static Builder<static>|Sku query()
 * @method static Builder<static>|Sku withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Sku withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Sku extends MediaModel
{
    use SoftDeletes;

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int|float => $value / 100,
            set: fn (float|int $value): int => (int) ($value * 100),
        );
    }
}
