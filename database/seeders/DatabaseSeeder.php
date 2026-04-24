<?php

declare(strict_types=1);

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

        if (app()->isLocal()) {
            $this->seedEcommerceData();
        }
    }

    private function seedEcommerceData(): void
    {
        $faker = app(\Faker\Generator::class);

        $categories = collect(['Electronics', 'Fashion', 'Home & Living'])->map(function ($name) {
            return \App\Models\Category::query()->firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name]
            );
        });

        $brands = collect($faker->ecommerceBrand('Electronics'))
            ->merge($faker->ecommerceBrand('Fashion'))
            ->merge($faker->ecommerceBrand('Home & Living'))
            ->unique()
            ->map(function ($name) {
                return \App\Models\Brand::query()->firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($name)],
                    ['name' => $name]
                );
            });

        if ($brands->count() < 15) {
            $brands = $brands->concat(\App\Models\Brand::factory()->count(15 - $brands->count())->create());
        }

        // Create products and orders only if missing
        if (\App\Models\Product::count() < 200) {
            // Create 200 products
            \App\Models\Product::factory()
                ->count(200)
                ->sequence(fn ($sequence) => [
                    'category_id' => $categories->random()->id,
                    'brand_id' => $brands->random()->id,
                ])
                ->create()
                ->each(function ($product) use ($faker) {
                    // 4-5 SKUs each
                    \App\Models\Sku::factory()
                        ->count($faker->numberBetween(4, 5))
                        ->create(['product_id' => $product->id]);
                });

            // Some extra users
            User::factory()->count(50)->create();

            // Some Payment Methods
            $paymentMethods = \App\Models\PaymentMethod::factory()->count(3)->create();

            // Some Orders
            \App\Models\Order::factory()
                ->count(30)
                ->create()
                ->each(function ($order) use ($faker) {
                    $skus = \App\Models\Sku::inRandomOrder()->take(rand(1, 3))->get();
                    foreach ($skus as $sku) {
                        \App\Models\OrderItem::factory()->create([
                            'order_id' => $order->id,
                            'sku_id' => $sku->id,
                        ]);
                    }

                    // Update totals (sum returns raw cents, cast expects human units)
                    $totalCents = (int) $order->items()->sum('subtotal');
                    $order->gross_total = $totalCents / 100;
                    $order->net_total = $totalCents / 100;
                    $order->saveQuietly();

                    // Payment for some
                    if ($faker->boolean(70)) {
                        \App\Models\Payment::factory()->create([
                            'order_id' => $order->id,
                            'payment_method_id' => \App\Models\PaymentMethod::inRandomOrder()->first()->id,
                            'amount' => $order->net_total, // net_total is already human unit (float)
                        ]);
                    }
                });
        }

        // Static Pages
        collect(['About Us', 'Contact Us', 'Privacy Policy', 'Terms of Service', 'Return Policy'])
            ->each(function ($title) {
                StaticPage::query()->firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($title)],
                    [
                        'title' => $title,
                        'type' => PageType::ContentPage,
                        'content' => fake()->paragraphs(3, true),
                    ]
                );
            });
    }
}
