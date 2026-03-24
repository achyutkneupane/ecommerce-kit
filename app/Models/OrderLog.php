<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property int $order_id
 * @property OrderStatus $status
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Order $order
 *
 * @method static Builder<static>|OrderLog newModelQuery()
 * @method static Builder<static>|OrderLog newQuery()
 * @method static Builder<static>|OrderLog query()
 * @method static Builder<static>|OrderLog whereCreatedAt($value)
 * @method static Builder<static>|OrderLog whereId($value)
 * @method static Builder<static>|OrderLog whereNotes($value)
 * @method static Builder<static>|OrderLog whereOrderId($value)
 * @method static Builder<static>|OrderLog whereStatus($value)
 * @method static Builder<static>|OrderLog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
