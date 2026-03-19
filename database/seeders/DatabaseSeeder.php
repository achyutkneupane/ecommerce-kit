<?php

namespace Database\Seeders;

use App\Enums\PageType;
use App\Enums\UserRole;
use App\Models\StaticPage;
use App\Models\User;
use App\Settings\SiteSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(
        SiteSettings $siteSettings
    ): void {
        if (app()->isLocal()) {
            User::query()->firstOrCreate([
                'email' => 'developer@test.com',
            ], [
                'name' => 'Ecommerce Developer',
                'role' => UserRole::Developer,
                'password' => bcrypt('password'),
            ]);

            User::query()->firstOrCreate([
                'email' => 'admin@test.com',
            ], [
                'name' => 'Ecommerce Admin',
                'role' => UserRole::Admin,
                'password' => bcrypt('password'),
            ]);

            User::query()->firstOrCreate([
                'email' => 'manager@test.com',
            ], [
                'name' => 'Ecommerce Manager',
                'role' => UserRole::Manager,
                'password' => bcrypt('password'),
            ]);

            User::query()->firstOrCreate([
                'email' => 'user@test.com',
            ], [
                'name' => 'Ecommerce User',
                'role' => UserRole::User,
                'password' => bcrypt('password'),
            ]);
        }

        StaticPage::query()->firstOrCreate([
            'slug' => 'landing-page',
            'type' => PageType::LandingPage,
        ], [
            'title' => 'Home',
            'description' => $siteSettings->description,
            'tags' => ['ecommerce', 'kit', 'product', 'livewire', 'laravel'],
        ]);

        StaticPage::query()->firstOrCreate([
            'slug' => 'about-us',
            'type' => PageType::ContentPage,
        ], [
            'title' => 'About Us',
            'description' => sprintf('Learn more about %s and our mission to provide quality content to the Laravel community.', $siteSettings->name),
            'tags' => ['about', 'ecommerce', 'laravel'],
            'content' => implode('', [
                '<p>Welcome to the Ecommerce Kit! This is a simple starter kit for building an ecommerce using Laravel and Tailwind CSS.</p>',
                '<p>The pages are SEO optimized and responsive. The process is simple: create a new laravel project using the starter kit, set up your database, change the page designs as you like, and start adding products!</p>',
                '<p>Feel free to contribute to the project on <a href="https://github.com/achyutkneupane/ecommerce-kit">GitHub</a> or reach out to me on <a href="https://www.linkedin.com/in/achyutneupane">LinkedIn</a>.</p>',
            ]),
        ]);
    }
}
