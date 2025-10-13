<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical;

use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Services\CanonicalUrlBuilder;
use Fkrzski\LaravelCanonical\Validation\BaseUrlValidator;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class CanonicalServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/canonical.php',
            'canonical'
        );

        $this->app->singleton(BaseUrlValidator::class);
        $this->app->singleton(CanonicalUrlBuilder::class);

        $this->app->singleton(CanonicalConfig::class, fn (Application $app): CanonicalConfig => new CanonicalConfig(
            $app->make(BaseUrlValidator::class)
        ));

        $this->app->singleton(CanonicalUrlGenerator::class, fn (Application $app): CanonicalUrlGenerator => new CanonicalUrlGenerator(
            $app->make(CanonicalConfig::class),
            $app->make(CanonicalUrlBuilder::class)
        ));
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
            BaseUrlValidator::class,
            CanonicalConfig::class,
            CanonicalUrlBuilder::class,
            CanonicalUrlGenerator::class,
        ];
    }
}
