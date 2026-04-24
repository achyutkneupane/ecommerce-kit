<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\Products\Resources\Skus\Pages\CreateSku;
use App\Filament\Resources\Products\Resources\Skus\Pages\EditSku;
use App\Filament\Resources\Products\Resources\Skus\Pages\ViewSku;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create(['role' => 'admin']);
    actingAs($this->user);
});

it('can render create sku page', function (): void {
    $product = Product::factory()->create();

    Livewire::test(CreateSku::class, [
        'parentRecord' => $product,
    ])->assertSuccessful();
});

it('can render view sku page', function (): void {
    $product = Product::factory()->create();
    $sku = Sku::factory()->create(['product_id' => $product->id]);

    Livewire::test(ViewSku::class, [
        'parentRecord' => $product,
        'record' => $sku->id,
    ])->assertSuccessful();
});

it('can render edit sku page', function (): void {
    $product = Product::factory()->create();
    $sku = Sku::factory()->create(['product_id' => $product->id]);

    Livewire::test(EditSku::class, [
        'parentRecord' => $product,
        'record' => $sku->id,
    ])->assertSuccessful();
});
