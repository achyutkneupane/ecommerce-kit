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
                    ->getStateUsing(fn ($record) => static::getCustomerRecord($record))
                    ->html(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('amount')
                    ->getStateUsing(fn ($record) => static::getAmountRecord($record))
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

        if ($order->discount > 0) {
            $discount = '<br><strong>Discount:</strong> '.number_format($order->discount, 2);
        } else {
            $discount = '';
        }

        if ($order->delivery_charge > 0) {
            $deliveryCharge = '<br><strong>Delivery Charge: </strong>'.number_format($order->delivery_charge, 2);
        } else {
            $deliveryCharge = '';
        }

        if ($order->tax > 0) {
            $tax = '<br><strong>Tax:</strong> '.number_format($order->tax, 2);
        } else {
            $tax = '';
        }

        $netTotal = '<br><strong>Net Total:</strong> '.number_format($order->net_total, 2);

        return (new HtmlString($grossTotal.$discount.$deliveryCharge.$tax.$netTotal))->toHtml();
    }
}
