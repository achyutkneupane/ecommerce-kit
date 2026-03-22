<?php

namespace App\Filament\Resources\Products\Resources\Skus\Pages;

use App\Filament\Resources\Products\Resources\Skus\SkuResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSku extends ViewRecord
{
    protected static string $resource = SkuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
