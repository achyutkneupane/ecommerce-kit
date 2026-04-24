<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\Sku;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Override;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    #[Override]
    protected function handleRecordCreation(array $data): Model
    {
        $skus = Sku::query()
            ->whereIn('id', array_column($data['items'] ?? [], 'sku_id'))
            ->get()
            ->keyBy('id');

        $grossTotal = 0;
        $items = [];

        foreach ($data['items'] ?? [] as $item) {
            $sku = $skus->get($item['sku_id']);

            $loopTotal = $sku->price * $item['quantity'];

            $grossTotal += $loopTotal;

            $items[] = [
                'sku_id' => $item['sku_id'],
                'product' => $sku->product->title,
                'sku_code' => $sku->code,
                'properties' => $sku->specifications,
                'unit_price' => $sku->price,
                'quantity' => $item['quantity'],
                'subtotal' => $loopTotal,
            ];
        }

        $netTotal = $grossTotal;

        $user = User::query()->firstOrCreate([
            'phone' => $data['phone'] ?? '',
        ], [
            'name' => $data['full_name'] ?? '',
            'email' => $data['email'] ?? '',
            'address' => $data['address'] ?? '',
        ]);

        $order = Order::query()
            ->create([
                'user_id' => $user->id,
                'full_name' => $data['full_name'] ?? '',
                'email' => $data['email'] ?? '',
                'phone' => $data['phone'] ?? '',
                'address' => $data['address'] ?? '',
                'delivery_instructions' => $data['delivery_instructions'] ?? '',
                'gross_total' => $grossTotal,
                'net_total' => $netTotal,
                'status' => OrderStatus::INITIATED,
            ]);

        $order->items()->createMany($items);

        return $order;
    }
}
