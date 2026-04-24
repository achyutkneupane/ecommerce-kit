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

    public function updated(Order $order): void
    {
        if ($order->isDirty('status') && $order->status === OrderStatus::DELIVERED) {
            $this->awardLoyaltyPoints($order);
        }
    }

    protected function awardLoyaltyPoints(Order $order): void
    {
        if (! $order->user_id) {
            return;
        }

        $settings = resolve(\App\Settings\LoyaltySettings::class);
        if (! $settings->enabled) {
            return;
        }

        $totalPoints = 0;
        $order->load('items.sku.product');

        foreach ($order->items as $item) {
            $sku = $item->sku;
            $product = $sku?->product;

            $mode = $sku?->loyalty_mode ?? $product?->loyalty_mode ?? $settings->mode;
            $amount = $sku?->loyalty_amount ?? $product?->loyalty_amount ?? $settings->amount;

            $totalPoints += match ($mode) {
                \App\Enums\LoyaltyMode::Flat => (int) ($amount * $item->quantity),
                \App\Enums\LoyaltyMode::Percentage => (int) (($item->subtotal * $amount) / 100),
            };
        }

        if ($totalPoints > 0) {
            $order->customer->increment('loyalty_points', $totalPoints);
        }
    }
}
