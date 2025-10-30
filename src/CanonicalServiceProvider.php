<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical;

use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Contracts\BaseUrlValidatorInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlGeneratorInterface;
use Fkrzski\LaravelCanonical\Services\CanonicalUrlBuilder;
use Fkrzski\LaravelCanonical\Validation\BaseUrlValidator;
use Fkrzski\LaravelCanonical\View\Components\Canonical;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

final class CanonicalServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/canonical.php',
            'canonical'
        );

        $this->app->singleton(BaseUrlValidatorInterface::class, BaseUrlValidator::class);
        $this->app->singleton(CanonicalUrlBuilderInterface::class, CanonicalUrlBuilder::class);

        $this->app->singleton(CanonicalConfigInterface::class, function (Application $app): CanonicalConfig {
            /** @var BaseUrlValidatorInterface $validator */
            $validator = $app->make(BaseUrlValidatorInterface::class);

            return new CanonicalConfig(
                validator: $validator,
            );
        });

        $this->app->singleton(CanonicalUrlGeneratorInterface::class, function (Application $app): CanonicalUrlGenerator {
            /** @var CanonicalConfigInterface $config */
            $config = $app->make(CanonicalConfigInterface::class);

            /** @var CanonicalUrlBuilderInterface $builder */
            $builder = $app->make(CanonicalUrlBuilderInterface::class);

            return new CanonicalUrlGenerator(
                config: $config,
                builder: $builder,
            );
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'canonical');

        Blade::component('canonical', Canonical::class);

        Blade::directive('canonical', fn (?string $expression = null): string => "<?php echo sprintf('<link rel=\"canonical\" href=\"%s\" />', e(canonical()->generate($expression))); ?>");

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/canonical.php' => config_path('canonical.php'),
            ], 'canonical-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/canonical'),
            ], 'canonical-views');
        }
    }

    /** @return array<int, string> */
    public function provides(): array
    {
        return [
            BaseUrlValidatorInterface::class,
            CanonicalConfigInterface::class,
            CanonicalUrlBuilderInterface::class,
            CanonicalUrlGeneratorInterface::class,
        ];
    }
}
