<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;
use App\Settings\SiteSettings;

class OrderObserver
{
    public function creating(Order $order): void
    {
        $siteSettings = resolve(SiteSettings::class);
        $prefix = $siteSettings->store_prefix ?? 'EK';
        $prefix .= 'O';

        $lastOrder = Order::query()->withoutGlobalScopes()->orderBy('id', 'desc')->first();

        $lastOrderCode = $lastOrder->code;
        $lastOrderNumber = $lastOrderCode ? (int) str_replace($prefix, '', $lastOrderCode) : 0;

        $order->code = $prefix.mb_str_pad((string) ($lastOrderNumber + 1), 5, '0', STR_PAD_LEFT);
    }

    public function created(Order $order): void
    {
        $order->logs()->create([
            'status' => $order->status,
        ]);
    }

    public function updated(Order $order): void
    {
        if ($order->isDirty('status')) {
            $order->logs()->create([
                'status' => $order->status,
            ]);
        }
    }
}
