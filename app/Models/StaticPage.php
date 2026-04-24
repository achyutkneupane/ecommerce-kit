<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use AchyutN\LaravelSEO\Contracts\HasMarkup;
use AchyutN\LaravelSEO\Data\Breadcrumb;
use AchyutN\LaravelSEO\Models\SEO;
use AchyutN\LaravelSEO\Schemas\PageSchema;
use AchyutN\LaravelSEO\Traits\InteractsWithSEO;
use App\Enums\PageType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Override;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $content
 * @property string|null $name
 * @property PageType $type
 * @property array<array-key, mixed>|null $tags
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read SEO|null $seo
 * @property-read string $url
 *
 * @method static Builder<static>|StaticPage findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder<static>|StaticPage newModelQuery()
 * @method static Builder<static>|StaticPage newQuery()
 * @method static Builder<static>|StaticPage query()
 * @method static Builder<static>|StaticPage whereContent($value)
 * @method static Builder<static>|StaticPage whereCreatedAt($value)
 * @method static Builder<static>|StaticPage whereDeletedAt($value)
 * @method static Builder<static>|StaticPage whereDescription($value)
 * @method static Builder<static>|StaticPage whereId($value)
 * @method static Builder<static>|StaticPage whereName($value)
 * @method static Builder<static>|StaticPage whereSlug($value)
 * @method static Builder<static>|StaticPage whereTags($value)
 * @method static Builder<static>|StaticPage whereTitle($value)
 * @method static Builder<static>|StaticPage whereType($value)
 * @method static Builder<static>|StaticPage whereUpdatedAt($value)
 * @method static Builder<static>|StaticPage withUniqueSlugConstraints(Model $model, string $attribute, array $config, string $slug)
 *
 * @mixin \Eloquent
 */
#[Fillable(['title', 'slug', 'description', 'content', 'name', 'type', 'tags'])]
final class StaticPage extends MediaModel implements HasMarkup
{
    use HasFactory;
    use HasTheSlug;
    use InteractsWithSEO;
    use PageSchema;

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categoryValue(): string
    {
        return $this->type->getLabel();
    }

    public function authorValue(): ?string
    {
        /** @phpstan-var string|null */
        return config('app.name');
    }

    public function authorUrlValue(): string
    {
        return route('landing-page');
    }

    public function publisherValue(): ?string
    {
        return $this->getAuthorValue();
    }

    public function publisherUrlValue(): ?string
    {
        return $this->getAuthorUrlValue();
    }

    public function urlValue(): string
    {
        if ($this->type === PageType::LandingPage) {
            return route('landing-page');
        }

        if ($this->type === PageType::IndexPage) {
            return route(sprintf('%s.index', $this->name));
        }

        return route('page.view', $this);
    }

    public function imageValue(): ?string
    {
        return $this->hasMedia('cover') ? $this->getLastMediaUrl('cover') : null;
    }

    /** @return array<Breadcrumb> */
    public function breadcrumbs(): array
    {
        if ($this->type === PageType::LandingPage) {
            return [
                new Breadcrumb($this->getTitleValue(), $this->getURLValue()),
            ];
        }

        return [
            new Breadcrumb('Home', route('landing-page')),
            new Breadcrumb($this->getTitleValue(), $this->getURLValue()),
        ];
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (): string => route('page.view', $this),
        );
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'type' => PageType::class,
            'tags' => 'array',
        ];
    }
}
