<?php

declare(strict_types=1);

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
});

test('can list products', function (): void {
    $products = Product::factory()->count(10)->create();

    Livewire::test(ListProducts::class)
        ->assertCanSeeTableRecords($products)
        ->assertSuccessful();
});

test('can render create product page', function (): void {
    Livewire::test(CreateProduct::class)
        ->assertSuccessful();
});

test('can create product', function (): void {
    $newData = Product::factory()->make();

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'title' => $newData->title,
            'category_id' => $newData->category_id,
            'brand_id' => $newData->brand_id,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $this->assertDatabaseHas(Product::class, [
        'title' => $newData->title,
    ]);
});

test('can render edit product page', function (): void {
    $product = Product::factory()->has(Sku::factory())->create();

    Livewire::test(EditProduct::class, [
        'record' => $product->slug,
    ])
        ->assertSuccessful();
});
