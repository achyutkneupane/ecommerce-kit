<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use App\Casts\Currency;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $code
 * @property int|null $user_id
 * @property string $full_name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string|null $delivery_instructions
 * @property OrderStatus $status
 * @property int|float $gross_total
 * @property int|float $discount
 * @property int|float $delivery_charge
 * @property int|float $tax
 * @property int|float $net_total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $customer
 * @property-read Collection<int, OrderItem> $items
 * @property-read int|null $items_count
 * @property-read Collection<int, OrderLog> $logs
 * @property-read int|null $logs_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, Payment> $payments
 * @property-read int|null $payments_count
 *
 * @method static Builder<static>|Order newModelQuery()
 * @method static Builder<static>|Order newQuery()
 * @method static Builder<static>|Order query()
 * @method static Builder<static>|Order whereAddress($value)
 * @method static Builder<static>|Order whereCode($value)
 * @method static Builder<static>|Order whereCreatedAt($value)
 * @method static Builder<static>|Order whereDeliveryCharge($value)
 * @method static Builder<static>|Order whereDeliveryInstructions($value)
 * @method static Builder<static>|Order whereDiscount($value)
 * @method static Builder<static>|Order whereEmail($value)
 * @method static Builder<static>|Order whereFullName($value)
 * @method static Builder<static>|Order whereGrossTotal($value)
 * @method static Builder<static>|Order whereId($value)
 * @method static Builder<static>|Order whereNetTotal($value)
 * @method static Builder<static>|Order wherePhone($value)
 * @method static Builder<static>|Order whereStatus($value)
 * @method static Builder<static>|Order whereTax($value)
 * @method static Builder<static>|Order whereUpdatedAt($value)
 * @method static Builder<static>|Order whereUserId($value)
 *
 * @mixin \Eloquent
 */
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

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'gross_total' => Currency::class,
            'discount' => Currency::class,
            'delivery_charge' => Currency::class,
            'tax' => Currency::class,
            'net_total' => Currency::class,
        ];
    }
}
