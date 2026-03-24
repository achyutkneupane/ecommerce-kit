<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property int $order_id
 * @property int|null $payment_method_id
 * @property int $amount
 * @property string|null $transaction_id
 * @property array<array-key, mixed>|null $payload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read PaymentMethod|null $method
 * @property-read Order $order
 *
 * @method static Builder<static>|Payment newModelQuery()
 * @method static Builder<static>|Payment newQuery()
 * @method static Builder<static>|Payment query()
 * @method static Builder<static>|Payment whereAmount($value)
 * @method static Builder<static>|Payment whereCreatedAt($value)
 * @method static Builder<static>|Payment whereId($value)
 * @method static Builder<static>|Payment whereOrderId($value)
 * @method static Builder<static>|Payment wherePayload($value)
 * @method static Builder<static>|Payment wherePaymentMethodId($value)
 * @method static Builder<static>|Payment whereTransactionId($value)
 * @method static Builder<static>|Payment whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
