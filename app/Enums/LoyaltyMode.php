<?php

declare(strict_types=1);

namespace App\Enums;

use BackedEnum;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum LoyaltyMode: string implements HasColor, HasIcon, HasLabel
{
    case Flat = 'flat';
    case Percentage = 'percentage';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Flat => Color::Blue,
            self::Percentage => Color::Green,
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Flat => 'Flat Amount',
            self::Percentage => 'Percentage',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Flat => Heroicon::Hashtag,
            self::Percentage => Heroicon::ReceiptPercent,
        };
    }
}
