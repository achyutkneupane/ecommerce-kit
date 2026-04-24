<?php

declare(strict_types=1);

use App\Enums\LoyaltyMode;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('loyalty.enabled', false);
        $this->migrator->add('loyalty.mode', LoyaltyMode::Flat->value);
        $this->migrator->add('loyalty.amount', 0);
    }
};
