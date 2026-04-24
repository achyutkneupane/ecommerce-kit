<?php

declare(strict_types=1);

namespace App\Providers;

use App\Agents\AntigravityAgent;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Boost\Boost;
use Override;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->extend(\Faker\Generator::class, function (\Faker\Generator $faker) {
            $faker->addProvider(new \App\Faker\EcommerceProvider($faker));

            return $faker;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Model::automaticallyEagerLoadRelationships();
        Vite::macro('image', fn (string $asset) => $this->asset('resources/assets/images/'.$asset));

        Gate::define('viewPulse', function (User $user): bool {
            return $user->role === UserRole::Developer;
        });

        if (app()->isProduction()) {
            URL::useOrigin(config('app.url'));
            URL::forceScheme('https');
        }

        if (app()->isLocal()) {
            Boost::registerAgent('antigravity', AntigravityAgent::class);
        }
    }
}
