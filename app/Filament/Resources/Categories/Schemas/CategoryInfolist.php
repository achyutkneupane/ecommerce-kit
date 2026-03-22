<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryInfolist
{

    public static function getSchema(int $depth = 0): array
    {
        if ($depth > 1) {
            return [];
        }

        return [
            Section::make()
                ->columns()
                ->columnSpanFull()
                ->components([
                    TextEntry::make('name')
                        ->url(fn (Category $record) => CategoryResource::getUrl('view', ['record' => $record]))
                        ->label('Name'),
                    TextEntry::make('parent.name')
                        ->label('Parent')
                        ->hidden(fn () => $depth > 0)
                        ->url(fn (Category $record) => $record->parent ? CategoryResource::getUrl('view', ['record' => $record->parent]) : null)
                        ->placeholder('-'),
                    RepeatableEntry::make('children')
                        ->contained(false)
                        ->columnSpanFull()
                        ->grid(3)
                        ->placeholder('No sub-categories found.')
                        ->label('Sub-categories')
                        ->schema(fn () => static::getSchema($depth + 1))
                        ->hidden(fn (Category $record) => $depth >= 1)
                ]),
        ];
    }
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(fn () => static::getSchema());
    }
}
