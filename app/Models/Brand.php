<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use AchyutN\LaravelHelpers\Traits\HasTheSlug;

class Brand extends MediaModel
{
    use HasTheSlug;

    protected string $sluggableColumn = 'name';
}
