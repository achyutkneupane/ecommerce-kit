<?php

declare(strict_types=1);

namespace App\Actions\Customers;

use App\Models\User;
use Filament\Schemas\Components\Utilities\Set;

class ApplyCustomerToOrder
{
    public function handle(Set $set, int $customerId): void
    {
        $customer = User::query()->find($customerId);

        if ($customer) {
            $set('full_name', $customer->name);
            $set('email', $customer->email);
            $set('phone', $customer->phone);
            $set('address', $customer->address);
        }
    }
}
