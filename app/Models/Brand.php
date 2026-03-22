<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, Product> $products
 * @property-read int|null $products_count
 *
 * @method static Builder<static>|Brand findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder<static>|Brand newModelQuery()
 * @method static Builder<static>|Brand newQuery()
 * @method static Builder<static>|Brand query()
 * @method static Builder<static>|Brand whereCreatedAt($value)
 * @method static Builder<static>|Brand whereId($value)
 * @method static Builder<static>|Brand whereName($value)
 * @method static Builder<static>|Brand whereSlug($value)
 * @method static Builder<static>|Brand whereUpdatedAt($value)
 * @method static Builder<static>|Brand withUniqueSlugConstraints(Model $model, string $attribute, array $config, string $slug)
 *
 * @mixin \Eloquent
 */
class Brand extends MediaModel
{
    use HasTheSlug;

    protected string $sluggableColumn = 'name';

    /** @return HasMany<Product> */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
