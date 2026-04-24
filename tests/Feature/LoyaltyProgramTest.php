<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\LoyaltyMode;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use App\Settings\LoyaltySettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it awards loyalty points when order is delivered', function () {
    $settings = resolve(LoyaltySettings::class);
    $settings->enabled = true;
    $settings->mode = LoyaltyMode::Flat;
    $settings->amount = 10;
    $settings->save();

    $user = User::factory()->create(['loyalty_points' => 0]);
    $product = Product::factory()->create();
    $sku = Sku::factory()->create(['product_id' => $product->id, 'price' => 1000]);

    $order = Order::factory()->create([
        'user_id' => $user->id,
        'status' => OrderStatus::INITIATED,
    ]);

    $order->items()->create([
        'sku_id' => $sku->id,
        'product' => $product->title,
        'sku_code' => $sku->code,
        'quantity' => 2,
        'unit_price' => 1000,
        'subtotal' => 2000,
    ]);

    $order->status = OrderStatus::DELIVERED;
    $order->save();

    $user->refresh();
    expect($user->loyalty_points)->toBe(20); // 10 points * 2 qty
});

test('it uses percentage mode for loyalty points', function () {
    $settings = resolve(LoyaltySettings::class);
    $settings->enabled = true;
    $settings->mode = LoyaltyMode::Percentage;
    $settings->amount = 5; // 5%
    $settings->save();

    $user = User::factory()->create(['loyalty_points' => 0]);
    $sku = Sku::factory()->create(['price' => 1000]);

    $order = Order::factory()->create(['user_id' => $user->id]);
    $order->items()->create([
        'sku_id' => $sku->id,
        'product' => 'Test Product',
        'sku_code' => 'TEST-SKU',
        'quantity' => 1,
        'unit_price' => 1000,
        'subtotal' => 1000,
    ]);

    $order->status = OrderStatus::DELIVERED;
    $order->save();

    $user->refresh();
    expect($user->loyalty_points)->toBe(50); // 5% of 1000
});

test('it respects product overrides', function () {
    $settings = resolve(LoyaltySettings::class);
    $settings->enabled = true;
    $settings->mode = LoyaltyMode::Flat;
    $settings->amount = 10;
    $settings->save();

    $user = User::factory()->create(['loyalty_points' => 0]);
    $product = Product::factory()->create([
        'loyalty_mode' => LoyaltyMode::Percentage,
        'loyalty_amount' => 10, // 10%
    ]);
    $sku = Sku::factory()->create(['product_id' => $product->id]);

    $order = Order::factory()->create(['user_id' => $user->id]);
    $order->items()->create([
        'sku_id' => $sku->id,
        'product' => $product->title,
        'sku_code' => $sku->code,
        'quantity' => 1,
        'unit_price' => 1000,
        'subtotal' => 1000,
    ]);

    $order->status = OrderStatus::DELIVERED;
    $order->save();

    $user->refresh();
    expect($user->loyalty_points)->toBe(100); // 10% of 1000
});

test('it respects SKU overrides', function () {
    $settings = resolve(LoyaltySettings::class);
    $settings->enabled = true;
    $settings->mode = LoyaltyMode::Flat;
    $settings->amount = 10;
    $settings->save();

    $user = User::factory()->create(['loyalty_points' => 0]);
    $product = Product::factory()->create([
        'loyalty_mode' => LoyaltyMode::Flat,
        'loyalty_amount' => 50,
    ]);
    $sku = Sku::factory()->create([
        'product_id' => $product->id,
        'loyalty_mode' => LoyaltyMode::Flat,
        'loyalty_amount' => 100,
    ]);

    $order = Order::factory()->create(['user_id' => $user->id]);
    $order->items()->create([
        'sku_id' => $sku->id,
        'product' => $product->title,
        'sku_code' => $sku->code,
        'quantity' => 1,
        'unit_price' => 1000,
        'subtotal' => 1000,
    ]);

    $order->status = OrderStatus::DELIVERED;
    $order->save();

    $user->refresh();
    expect($user->loyalty_points)->toBe(100);
});

test('it does not award points if disabled', function () {
    $settings = resolve(LoyaltySettings::class);
    $settings->enabled = false;
    $settings->save();

    $user = User::factory()->create(['loyalty_points' => 0]);
    $sku = Sku::factory()->create();

    $order = Order::factory()->create(['user_id' => $user->id]);
    $order->items()->create([
        'sku_id' => $sku->id,
        'product' => 'Test Product',
        'sku_code' => 'TEST-SKU',
        'quantity' => 1,
        'subtotal' => 1000,
        'unit_price' => 1000,
    ]);

    $order->status = OrderStatus::DELIVERED;
    $order->save();

    $user->refresh();
    expect($user->loyalty_points)->toBe(0);
});
