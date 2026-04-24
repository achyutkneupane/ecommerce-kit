<?php

declare(strict_types=1);

namespace App\Filament\Components;

use App\Actions\Customers\ApplyCustomerToOrder;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

final class SearchCustomerAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Search Customer');

        $this->icon(Heroicon::MagnifyingGlass);

        $this->color(Color::Green);

        $this->slideOver();

        $this->schema([
            Select::make('customer')
                ->label('Customer')
                ->options(fn () => User::query()->whereRole('user')->pluck('name', 'id'))
                ->searchable()
                ->native(false)
                ->getSearchResultsUsing(
                    function (string $search) {
                        return User::query()
                            ->whereRole('user')
                            ->where(function ($query) use ($search): void {
                                $query->whereRaw('LOWER(name) like ?', ['%'.mb_strtolower($search).'%'])
                                    ->orWhereRaw('LOWER(email) like ?', ['%'.mb_strtolower($search).'%'])
                                    ->orWhereRaw('LOWER(phone) like ?', ['%'.mb_strtolower($search).'%']);
                            })
                            ->pluck('name', 'id');
                    }
                )
                ->live()
                ->afterStateUpdated(fn (Set $set, ?string $state, ApplyCustomerToOrder $applyCustomerToOrder): mixed => $state ? $applyCustomerToOrder->handle($set, (int) $state) : $set('customer_details', null))
                ->preload(),
            Section::make('Customer Details')
                ->hidden(fn (Get $get): bool => is_null($get('customer')))
                ->contained(false)
                ->schema([
                    TextEntry::make('full_name')
                        ->inlineLabel()
                        ->label('Name'),
                    TextEntry::make('email')
                        ->inlineLabel()
                        ->label('Email'),
                    TextEntry::make('phone')
                        ->inlineLabel()
                        ->label('Phone'),
                    TextEntry::make('address')
                        ->inlineLabel()
                        ->label('Address'),
                ]),
        ]);

        $this->modalSubmitActionLabel('Enter Details');

        $this->action(fn (Set $set, array $data, ApplyCustomerToOrder $applyCustomerToOrder) => $applyCustomerToOrder->handle($set, (int) $data['customer']));
    }

    public static function getDefaultName(): string
    {
        return 'search_customer';
    }
}
