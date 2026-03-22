<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Resources\Skus;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Resources\Skus\Pages\CreateSku;
use App\Filament\Resources\Products\Resources\Skus\Pages\EditSku;
use App\Filament\Resources\Products\Resources\Skus\Pages\ViewSku;
use App\Filament\Resources\Products\Resources\Skus\Schemas\SkuForm;
use App\Filament\Resources\Products\Resources\Skus\Schemas\SkuInfolist;
use App\Filament\Resources\Products\Resources\Skus\Tables\SkusTable;
use App\Models\Sku;
use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SkuResource extends Resource
{
    protected static ?string $model = Sku::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::SquaresPlus;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::OutlinedSquaresPlus;

    protected static ?string $parentResource = ProductResource::class;

    protected static ?string $label = 'SKU';

    protected static ?string $pluralLabel = 'SKUs';

    protected static ?string $recordTitleAttribute = 'code';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Schema $schema): Schema
    {
        return SkuForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SkuInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SkusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateSku::route('/create'),
            'view' => ViewSku::route('/{record}'),
            'edit' => EditSku::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewSku::class,
            EditSku::class,
        ]);
    }
}
