<?php

declare(strict_types=1);

namespace App\Models;

use AchyutN\LaravelHelpers\Models\MediaModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends MediaModel
{
    use SoftDeletes;
}
