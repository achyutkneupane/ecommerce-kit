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

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case INITIATED = 'initiated'; // default value in migration. Don't change the value
    case PAYMENT_RECEIVED = 'payment_received';
    case DISPATCHED = 'dispatched';
    case DELIVERED = 'delivered';
    case CANCELLED_BY_CUSTOMER = 'cancelled_by_customer';
    case CANCELLED_BY_SYSTEM = 'cancelled_by_system';
    case RETURNED = 'returned';
    case REFUNDED = 'refunded';
    case FAILED = 'failed';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::INITIATED => Color::Yellow,
            self::PAYMENT_RECEIVED => Color::Blue,
            self::DISPATCHED => Color::Indigo,
            self::DELIVERED => Color::Green,
            self::CANCELLED_BY_CUSTOMER, self::CANCELLED_BY_SYSTEM => Color::Red,
            self::RETURNED => Color::Orange,
            self::REFUNDED => Color::Teal,
            self::FAILED => Color::Gray,
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::INITIATED => 'Initiated',
            self::PAYMENT_RECEIVED => 'Payment Received',
            self::DISPATCHED => 'Dispatched',
            self::DELIVERED => 'Delivered',
            self::CANCELLED_BY_CUSTOMER => 'Cancelled by Customer',
            self::CANCELLED_BY_SYSTEM => 'Cancelled by System',
            self::RETURNED => 'Returned',
            self::REFUNDED => 'Refunded',
            self::FAILED => 'Failed',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::INITIATED => Heroicon::Play,
            self::PAYMENT_RECEIVED => Heroicon::CreditCard,
            self::DISPATCHED => Heroicon::Truck,
            self::DELIVERED => Heroicon::CheckCircle,
            self::CANCELLED_BY_CUSTOMER, self::CANCELLED_BY_SYSTEM => Heroicon::XCircle,
            self::RETURNED => Heroicon::ArrowPath,
            self::REFUNDED => Heroicon::CurrencyDollar,
            self::FAILED => Heroicon::ExclamationTriangle,
        };
    }
}
