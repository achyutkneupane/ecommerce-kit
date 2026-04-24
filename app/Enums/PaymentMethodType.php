<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum PaymentMethodType: string implements HasColor, HasLabel
{
    case COD = 'cod';
    case SCREENSHOT = 'screenshot';
    case TEXT = 'text'; // default value in migration. Don't change the value
    case THIRD_PARTY = 'third_party';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::COD => Color::Green,
            self::SCREENSHOT => Color::Blue,
            self::TEXT => Color::Yellow,
            self::THIRD_PARTY => Color::Indigo,
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::COD => 'Cash on Delivery',
            self::SCREENSHOT => 'Screenshot',
            self::TEXT => 'Text',
            self::THIRD_PARTY => 'Third Party',
        };
    }
}
