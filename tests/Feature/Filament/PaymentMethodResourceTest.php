<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Enums\PaymentMethodType;
use App\Filament\Resources\PaymentMethods\Pages\ManagePaymentMethods;
use App\Models\PaymentMethod;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create(['role' => 'admin']);
    actingAs($this->user);
});

it('can render payment method list page', function (): void {
    PaymentMethod::factory()->count(5)->create();

    Livewire::test(ManagePaymentMethods::class)
        ->assertSuccessful();
});

it('can create payment method', function (): void {
    Livewire::test(ManagePaymentMethods::class)
        ->callAction('create', data: [
            'name' => 'Cash on Delivery',
            'type' => PaymentMethodType::COD->value,
            'is_active' => true,
        ])
        ->assertHasNoActionErrors();

    $this->assertDatabaseHas('payment_methods', [
        'name' => 'Cash on Delivery',
        'type' => PaymentMethodType::COD->value,
    ]);
});

it('can edit payment method', function (): void {
    $paymentMethod = PaymentMethod::factory()->create([
        'type' => PaymentMethodType::COD,
    ]);

    Livewire::test(ManagePaymentMethods::class)
        ->callTableAction('edit', $paymentMethod, data: [
            'name' => 'Updated Name',
            'type' => PaymentMethodType::TEXT->value,
            'payment_instructions' => 'New instructions',
        ])
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('payment_methods', [
        'id' => $paymentMethod->id,
        'name' => 'Updated Name',
        'type' => PaymentMethodType::TEXT->value,
    ]);
});
