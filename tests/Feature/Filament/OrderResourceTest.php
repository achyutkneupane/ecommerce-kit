<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create(['role' => 'admin']);
    actingAs($this->user);
});

it('can render order list page', function (): void {
    Order::factory()->count(10)->create();

    Livewire::test(ListOrders::class)
        ->assertSuccessful();
});

it('can render view order page', function (): void {
    $order = Order::factory()->create();

    Livewire::test(ViewOrder::class, [
        'record' => $order->id,
    ])->assertSuccessful();
});

it('can render edit order page', function (): void {
    $order = Order::factory()->create();

    Livewire::test(EditOrder::class, [
        'record' => $order->id,
    ])->assertSuccessful();
});
