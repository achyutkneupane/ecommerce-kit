<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code'),
                TextColumn::make('customer')
                    ->getStateUsing(fn (Order $order): string => static::getCustomerRecord($order))
                    ->html(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('amount')
                    ->getStateUsing(fn (Order $order): string => static::getAmountRecord($order))
                    ->html(),
                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->numeric(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getCustomerRecord(Order $order): string
    {
        $fullName = '<strong>'.$order->full_name.'</strong>';
        $email = '<a href="mailto:'.$order->email.'">'.$order->email.'</a>';
        $phone = '<a href="tel:'.$order->phone.'">'.$order->phone.'</a>';
        $address = '<div>'.nl2br(e($order->address)).'</div>';

        return (new HtmlString($fullName.'<br>'.$email.'<br>'.$phone.'<br>'.$address))->toHtml();
    }

    public static function getAmountRecord(Order $order): string
    {
        $grossTotal = '<strong>Gross Total:</strong> '.number_format($order->gross_total, 2);

        $discount = $order->discount > 0 ? '<br><strong>Discount:</strong> '.number_format($order->discount, 2) : '';

        if ($order->delivery_charge > 0) {
            $deliveryCharge = '<br><strong>Delivery Charge: </strong>'.number_format($order->delivery_charge, 2);
        } else {
            $deliveryCharge = '';
        }

        $tax = $order->tax > 0 ? '<br><strong>Tax:</strong> '.number_format($order->tax, 2) : '';

        $netTotal = '<br><strong>Net Total:</strong> '.number_format($order->net_total, 2);

        return (new HtmlString($grossTotal.$discount.$deliveryCharge.$tax.$netTotal))->toHtml();
    }
}
