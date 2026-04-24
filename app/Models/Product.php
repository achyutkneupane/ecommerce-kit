<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use AchyutN\LaravelSEO\Contracts\HasMarkup;
use AchyutN\LaravelSEO\Schemas\ProductSchema;
use AchyutN\LaravelSEO\Traits\InteractsWithSEO;
use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Override;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string|null $code
 * @property string $title
 * @property string $slug
 * @property int $category_id
 * @property int|null $brand_id
 * @property array<array-key, mixed>|null $specifications
 * @property int $sku_sequence
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Brand|null $brand
 * @property-read Category $category
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read int $quantity
 * @property-read Collection<int, Sku> $skus
 * @property-read int|null $skus_count
 *
 * @method static Builder<static>|Product findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder<static>|Product newModelQuery()
 * @method static Builder<static>|Product newQuery()
 * @method static Builder<static>|Product onlyTrashed()
 * @method static Builder<static>|Product query()
 * @method static Builder<static>|Product whereBrandId($value)
 * @method static Builder<static>|Product whereCategoryId($value)
 * @method static Builder<static>|Product whereCode($value)
 * @method static Builder<static>|Product whereCreatedAt($value)
 * @method static Builder<static>|Product whereDeletedAt($value)
 * @method static Builder<static>|Product whereId($value)
 * @method static Builder<static>|Product whereSkuSequence($value)
 * @method static Builder<static>|Product whereSlug($value)
 * @method static Builder<static>|Product whereSpecifications($value)
 * @method static Builder<static>|Product whereTitle($value)
 * @method static Builder<static>|Product whereUpdatedAt($value)
 * @method static Builder<static>|Product withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Product withUniqueSlugConstraints(Model $model, string $attribute, array $config, string $slug)
 * @method static Builder<static>|Product withoutTrashed()
 *
 * @mixin \Eloquent
 */
#[Fillable(['title', 'slug', 'category_id', 'brand_id', 'specifications', 'sku_sequence'])]
#[ObservedBy(ProductObserver::class)]
class Product extends MediaModel implements HasMarkup
{
    use HasFactory;
    use HasTheSlug;
    use InteractsWithSEO;
    use ProductSchema;
    use SoftDeletes;

    #[Scope]
    public static function available(Builder $builder): Builder
    {
        return $builder->whereHas('skus', function (Builder $builder): Builder {
            return $builder->where('quantity', '>', 0);
        });
    }

    /** @return BelongsTo<Category> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return BelongsTo<Brand> */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /** @return HasMany<Sku> */
    public function skus(): HasMany
    {
        return $this->hasMany(Sku::class);
    }

    protected function quantity(): Attribute
    {
        return Attribute::get(fn ($value): int => (int) $this->loadSum('skus', 'quantity')->skus_sum_quantity ?? 0);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'specifications' => 'array',
        ];
    }
}
