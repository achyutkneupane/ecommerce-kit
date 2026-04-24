<?php

declare(strict_types=1);

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
});

test('can list categories', function (): void {
    $categories = Category::factory()->count(10)->create();

    Livewire::test(ListCategories::class)
        ->assertCanSeeTableRecords($categories)
        ->assertSuccessful();
});

test('can render create category page', function (): void {
    Livewire::test(CreateCategory::class)
        ->assertSuccessful();
});

test('can create category', function (): void {
    $newData = Category::factory()->make();

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => $newData->name,
            'specifications' => $newData->specifications,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Category::class, [
        'name' => $newData->name,
    ]);
});

test('can render edit category page', function (): void {
    $category = Category::factory()->create();

    Livewire::test(EditCategory::class, [
        'record' => $category->slug,
    ])
        ->assertSuccessful();
});
