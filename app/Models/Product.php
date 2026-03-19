<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use AchyutN\LaravelHelpers\Traits\HasTheSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends MediaModel
{
    use HasTheSlug;
    use SoftDeletes;
}
