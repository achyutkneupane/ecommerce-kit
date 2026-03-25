<?php

declare(strict_types=1);

namespace App\Filament\Resources\PaymentMethods;

use App\Enums\PaymentMethodType;
use App\Filament\Resources\PaymentMethods\Pages\ManagePaymentMethods;
use App\Models\PaymentMethod;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleStack;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('is_active')
                    ->label('Is Active')
                    ->columnSpanFull()
                    ->default(true)
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options(PaymentMethodType::class)
                    ->live()
                    ->required(),
                SpatieMediaLibraryFileUpload::make('cover')
                    ->label('Screenshot')
                    ->collection('cover')
                    ->visible(fn (Get $get): bool => $get('type') === PaymentMethodType::SCREENSHOT)
                    ->helperText('')
                    ->image()
                    ->required()
                    ->preserveFilenames()
                    ->imageEditor()
                    ->openable()
                    ->columnSpanFull()
                    ->previewable()
                    ->downloadable()
                    ->deletable()
                    ->rules([
                        'required',
                    ]),
                RichEditor::make('payment_instructions')
                    ->default(fn (): string => '<p>Please use <strong>{order_id}</strong> in remarks of the payment.</p>')
                    ->helperText('Use {order_id} where you want to display the order ID in the instructions.')
                    ->visible(fn (Get $get): bool => in_array($get('type'), [PaymentMethodType::TEXT, PaymentMethodType::SCREENSHOT]))
                    ->columnSpanFull(),
                KeyValue::make('settings')
                    ->visible(fn (Get $get): bool => $get('type') === PaymentMethodType::THIRD_PARTY)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                IconEntry::make('is_active')
                    ->columnSpanFull()
                    ->boolean(),
                TextEntry::make('name'),
                TextEntry::make('type')
                    ->badge(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->searchable(),
                ToggleColumn::make('is_active'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePaymentMethods::route('/'),
        ];
    }
}
