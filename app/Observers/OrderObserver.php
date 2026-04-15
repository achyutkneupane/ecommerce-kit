<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Settings\SiteSettings;

class OrderObserver
{
    public function created(Order $order): void
    {
        $siteSettings = resolve(SiteSettings::class);
        $prefix = $siteSettings->store_prefix ?? 'EK';
        $prefix .= 'O';

        $order->code = $prefix.mb_str_pad((string) $order->id, 5, '0', STR_PAD_LEFT);
        $order->saveQuietly();

        $order->logs()->create([
            'status' => $order->status ?? OrderStatus::INITIATED,
        ]);
    }

    public function updating(Order $order): void
    {
        if ($order->isDirty('status')) {
            $order->logs()->create([
                'status' => $order->status,
            ]);
        }
    }
}
