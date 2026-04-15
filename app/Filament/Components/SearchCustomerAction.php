<?php

declare(strict_types=1);

namespace App\Filament\Components;

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
                ->afterStateUpdated(function (Set $set, string $state): void {
                    $customer = User::query()->find($state);
                    if ($customer) {
                        $set('full_name', $customer->name);
                        $set('email', $customer->email);
                        $set('phone', $customer->phone);
                        $set('address', $customer->address);
                    } else {
                        $set('customer_details', null);
                    }
                })
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

        $this->action(function (Set $set, array $data): void {
            $customerId = $data['customer'];
            $customer = User::query()->find($customerId);

            if ($customer) {
                $set('name', $customer->name);
                $set('email', $customer->email);
                $set('phone', $customer->phone);
                $set('address', $customer->address);
            }
        });
    }

    public static function getDefaultName(): string
    {
        return 'search_customer';
    }
}
