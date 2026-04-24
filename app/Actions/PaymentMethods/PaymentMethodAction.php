<?php

declare(strict_types=1);

namespace App\Actions\PaymentMethods;

use App\Enums\PaymentMethodType;

class PaymentMethodAction
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function mutateRecordData(array $data): array
    {
        $type = $data['type'] ?? null;
        $text = null;

        $typeEnum = $type instanceof PaymentMethodType ? $type : PaymentMethodType::tryFrom((string) $type);

        if (in_array($typeEnum, [PaymentMethodType::TEXT, PaymentMethodType::SCREENSHOT])) {
            $text = $data['settings']['text'] ?? '';
        }

        return [
            'name' => $data['name'],
            'type' => $type,
            'payment_instructions' => $text,
            'settings' => $data['settings'] ?? [],
            'is_active' => $data['is_active'] ?? true,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function mutateData(array $data): array
    {
        $type = $data['type'] ?? null;
        $typeEnum = $type instanceof PaymentMethodType ? $type : PaymentMethodType::tryFrom((string) $type);

        $settings = match ($typeEnum) {
            PaymentMethodType::TEXT, PaymentMethodType::SCREENSHOT => [
                'text' => $data['payment_instructions'] ?? '',
            ],
            PaymentMethodType::THIRD_PARTY => $data['settings'] ?? [],
            PaymentMethodType::COD, null => null,
        };

        return [
            'name' => $data['name'],
            'type' => $type,
            'settings' => $settings,
            'is_active' => $data['is_active'] ?? true,
        ];
    }
}
