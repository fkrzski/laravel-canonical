<?php

declare(strict_types=1);

namespace VendorName\Skeleton;

use Illuminate\Support\ServiceProvider;

final class SkeletonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/skeleton.php',
            'skeleton'
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/skeleton.php' => config_path('skeleton.php'),
            ], 'skeleton-config');

            // $this->publishes([
            //     __DIR__.'/../database/migrations' => database_path('migrations'),
            // ], 'skeleton-migrations');

            // $this->publishes([
            //     __DIR__.'/../resources/views' => resource_path('views/vendor/skeleton'),
            // ], 'skeleton-views');

            // $this->publishes([
            //     __DIR__.'/../resources/lang' => $this->app->langPath('vendor/skeleton'),
            // ], 'skeleton-lang');

            // $this->commands([
            //     // YourCommand::class,
            // ]);
        }

        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'skeleton');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'skeleton');
        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
