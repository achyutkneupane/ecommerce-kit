<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PageType;
use App\Enums\UserRole;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Sku;
use App\Models\StaticPage;
use App\Models\User;
use App\Settings\SiteSettings;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        $generator = resolve(Generator::class);

        $categories = collect(['Electronics', 'Fashion', 'Home & Living'])->map(function ($name) {
            return Category::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        });

        $brands = collect($generator->ecommerceBrand('Electronics'))
            ->merge($generator->ecommerceBrand('Fashion'))
            ->merge($generator->ecommerceBrand('Home & Living'))
            ->unique()
            ->map(function ($name) {
                return Brand::query()->firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );
            });

        if ($brands->count() < 15) {
            $brands = $brands->concat(Brand::factory()->count(15 - $brands->count())->create());
        }

        // Create products and orders only if missing
        if (Product::query()->count() < 200) {
            // Create 200 products
            Product::factory()
                ->count(200)
                ->sequence(fn ($sequence): array => [
                    'category_id' => $categories->random()->id,
                    'brand_id' => $brands->random()->id,
                ])
                ->create()
                ->each(function ($product) use ($generator): void {
                    // 4-5 SKUs each
                    Sku::factory()
                        ->count($generator->numberBetween(4, 5))
                        ->create(['product_id' => $product->id]);
                });

            // Some extra users
            User::factory()->count(50)->create();

            // Some Payment Methods
            $paymentMethods = PaymentMethod::factory()->count(3)->create();

            // Some Orders
            Order::factory()
                ->count(30)
                ->create()
                ->each(function ($order) use ($generator): void {
                    $skus = Sku::query()->inRandomOrder()->take(rand(1, 3))->get();
                    foreach ($skus as $sku) {
                        OrderItem::factory()->create([
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
                    if ($generator->boolean(70)) {
                        Payment::factory()->create([
                            'order_id' => $order->id,
                            'payment_method_id' => PaymentMethod::query()->inRandomOrder()->first()->id,
                            'amount' => $order->net_total, // net_total is already human unit (float)
                        ]);
                    }
                });
        }

        // Static Pages
        collect(['About Us', 'Contact Us', 'Privacy Policy', 'Terms of Service', 'Return Policy'])
            ->each(function ($title): void {
                StaticPage::query()->firstOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'title' => $title,
                        'type' => PageType::ContentPage,
                        'content' => fake()->paragraphs(3, true),
                    ]
                );
            });
    }
}
