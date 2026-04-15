<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\Sku;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function handleRecordCreation(array $data): Model
    {
//        array:6 [▼ // app/Filament/Resources/Orders/Pages/CreateOrder.php:17
//  "full_name" => "Ecommerce User"
//  "email" => "user@test.com"
//  "phone" => "9860323771"
//  "address" => "Dharan"
//  "delivery_instructions" => null
//  "items" => array:2 [▼
//    0 => array:2 [▼
//      "sku_id" => 1
//      "quantity" => 2.0
//    ]
//    1 => array:2 [▼
//      "sku_id" => 2
//      "quantity" => 2.0
//    ]
//  ]
//]

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
                'product' => $sku->product->name,
                'sku_code' => $sku->code,
                'properties' => $sku->specifications,
                'unit_price' => $sku->price,
                'quantity' => $item['quantity'],
                'subtotal' => $loopTotal,
            ];
        }

        $order = Order::query()
            ->create([
                'full_name' => $data['full_name'] ?? '',
                'email' => $data['email'] ?? '',
                'phone' => $data['phone'] ?? '',
                'address' => $data['address'] ?? '',
                'delivery_instructions' => $data['delivery_instructions'] ?? '',
                'gross_total' => $grossTotal,
            ]);

        $order->items()->createMany($items);

        return $order;
    }
}
