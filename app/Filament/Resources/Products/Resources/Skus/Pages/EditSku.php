<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Resources\Skus\Pages;

use App\Filament\Resources\Products\Resources\Skus\SkuResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Override;

class EditSku extends EditRecord
{
    protected static string $resource = SkuResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
