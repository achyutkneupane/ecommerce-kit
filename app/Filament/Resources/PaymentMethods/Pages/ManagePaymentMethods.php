<?php

declare(strict_types=1);

namespace App\Filament\Resources\PaymentMethods\Pages;

use App\Enums\PaymentMethodType;
use App\Filament\Resources\PaymentMethods\PaymentMethodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentMethods extends ManageRecords
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateDataUsing(function (array $data): array {
                    /** @var PaymentMethodType $type */
                    $type = $data['type'] ?? null;

                    $settings = match ($type) {
                        PaymentMethodType::TEXT, PaymentMethodType::SCREENSHOT => [
                            'text' => $data['payment_instructions'] ?? '',
                        ],
                        PaymentMethodType::THIRD_PARTY => $data['settings'] ?? [],
                        PaymentMethodType::COD => null,
                    };

                    return [
                        'name' => $data['name'],
                        'type' => $type,
                        'settings' => $settings,
                        'is_active' => $data['is_active'] ?? true,
                    ];
                }),
        ];
    }
}
