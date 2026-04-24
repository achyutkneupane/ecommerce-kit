<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\Brands\Pages\ManageBrands;
use App\Models\Brand;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create(['role' => 'admin']);
    actingAs($this->user);
});

it('can render brand list page', function (): void {
    Brand::factory()->count(10)->create();

    Livewire::test(ManageBrands::class)
        ->assertSuccessful();
});

it('can create brand', function (): void {
    Livewire::test(ManageBrands::class)
        ->callAction('create', data: [
            'name' => 'Test Brand',
        ])
        ->assertHasNoActionErrors();

    $this->assertDatabaseHas('brands', [
        'name' => 'Test Brand',
    ]);
});

it('can edit brand', function (): void {
    $brand = Brand::factory()->create();

    Livewire::test(ManageBrands::class)
        ->callTableAction('edit', $brand, data: [
            'name' => 'Updated Brand',
        ])
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('brands', [
        'id' => $brand->id,
        'name' => 'Updated Brand',
    ]);
});
