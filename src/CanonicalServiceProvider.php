<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class CanonicalServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/canonical.php',
            'canonical'
        );

        $this->app->singleton(LaravelCanonicalClass::class, fn (): LaravelCanonicalClass => new LaravelCanonicalClass);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/canonical.php' => config_path('canonical.php'),
            ], 'canonical-config');
        }
    }

    /** @return array<int, string> */
    public function provides(): array
    {
        return [
            LaravelCanonicalClass::class,
        ];
    }
}
