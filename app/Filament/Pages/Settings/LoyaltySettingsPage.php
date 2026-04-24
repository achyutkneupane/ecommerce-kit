<?php

declare(strict_types=1);

namespace App\Filament\Pages\Settings;

use App\Enums\LoyaltyMode;
use App\Settings\LoyaltySettings;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Override;
use UnitEnum;

class LoyaltySettingsPage extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static string $settings = LoyaltySettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Loyalty Program')
                    ->description('Manage global loyalty program configuration')
                    ->schema([
                        Toggle::make('enabled')
                            ->label('Enable Loyalty Program')
                            ->live(),
                        Select::make('mode')
                            ->options(LoyaltyMode::class)
                            ->required()
                            ->hidden(fn (callable $get): bool => ! $get('enabled'))
                            ->live(),
                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->hidden(fn (callable $get): bool => ! $get('enabled'))
                            ->label(fn (callable $get): string => $get('mode') === LoyaltyMode::Percentage->value ? 'Percentage (%)' : 'Points per Unit'),
                    ]),
            ]);
    }
}
