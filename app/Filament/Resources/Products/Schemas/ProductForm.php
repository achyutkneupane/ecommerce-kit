<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Product Title')
                    ->columnSpanFull()
                    ->required(),
                Select::make('category_id')
                    ->label('Category')
                    ->options(
                        fn () => Category::query()
                            ->select('id', 'name', 'parent_id')
                            ->get()
                            ->groupBy(fn ($category) => $category->parent->name ?? 'Parent Categories')
                            ->map(fn ($group) => $group->pluck('name', 'id'))
                            ->toArray()
                    )
                    ->afterStateUpdated(
                        function (Set $set, ?int $state) {
                            if ($state) {
                                $specifications = Category::find($state)->specifications;
                                $mapped = array_map(
                                    fn ($specification) => [
                                        'key' => $specification,
                                        'value' => '',
                                    ],
                                    $specifications
                                );

                                $set('specifications', $mapped);
                            }
                        }
                    )
                    ->searchable()
                    ->live()
                    ->required(),
                Select::make('brand_id')
                    ->searchable()
                    ->preload()
                    ->relationship('brand', 'name'),
                KeyValue::make('specifications')
                    ->helperText('Only the common specifications which won\'t be different in SKUs')
                    ->hiddenJs(<<<'JS'
                        $get('category_id') === null
                    JS)
                    ->columnSpanFull(),
            ]);
    }
}
