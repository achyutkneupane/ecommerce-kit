<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use App\Enums\PaymentMethodType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $name
 * @property PaymentMethodType $type
 * @property array<array-key, mixed>|null $settings
 * @property int $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, Payment> $payments
 * @property-read int|null $payments_count
 *
 * @method static Builder<static>|PaymentMethod newModelQuery()
 * @method static Builder<static>|PaymentMethod newQuery()
 * @method static Builder<static>|PaymentMethod query()
 * @method static Builder<static>|PaymentMethod whereCreatedAt($value)
 * @method static Builder<static>|PaymentMethod whereId($value)
 * @method static Builder<static>|PaymentMethod whereIsActive($value)
 * @method static Builder<static>|PaymentMethod whereName($value)
 * @method static Builder<static>|PaymentMethod whereSettings($value)
 * @method static Builder<static>|PaymentMethod whereType($value)
 * @method static Builder<static>|PaymentMethod whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
            'is_active' => 'boolean',
        ];
    }
}
