<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Resources\Skus\Pages;

use App\Filament\Resources\Products\Resources\Skus\SkuResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSku extends CreateRecord
{
    protected static string $resource = SkuResource::class;
}
