<?php

declare(strict_types=1);

namespace App\Settings;

use App\Enums\LoyaltyMode;
use Spatie\LaravelSettings\Settings;

final class LoyaltySettings extends Settings
{
    public bool $enabled;

    public LoyaltyMode $mode;

    public int $amount; // points for flat or percentage points (e.g. 5 for 5%)

    public static function group(): string
    {
        return 'loyalty';
    }
}
