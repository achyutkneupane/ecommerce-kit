<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.name', 'Ecommerce Kit');
        $this->migrator->add('site.description', 'The starter kit for Laravel13 with ecommerce features and filament v5.');
        $this->migrator->add('site.logo', '');
        $this->migrator->add('site.favicon', '');
        $this->migrator->add('site.og_image', '');
        $this->migrator->add('site.header_scripts', '');
        $this->migrator->add('site.footer_scripts', '');
        $this->migrator->add('site.robots_txt', '');
    }
};
