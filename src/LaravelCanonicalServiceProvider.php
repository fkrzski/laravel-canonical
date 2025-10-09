<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical;

use Illuminate\Support\ServiceProvider;

final class LaravelCanonicalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-canonical.php',
            'laravel-canonical'
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laravel-canonical.php' => config_path('laravel-canonical.php'),
            ], 'laravel-canonical-config');
        }
    }
}
