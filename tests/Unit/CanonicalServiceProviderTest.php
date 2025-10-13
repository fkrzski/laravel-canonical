<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalServiceProvider;
use Fkrzski\LaravelCanonical\CanonicalUrlGenerator;
use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Services\CanonicalUrlBuilder;
use Fkrzski\LaravelCanonical\Validation\BaseUrlValidator;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

mutates(CanonicalServiceProvider::class);

describe('CanonicalServiceProvider', function (): void {
    it('extends ServiceProvider class', function (): void {
        expect(new CanonicalServiceProvider($this->app))->toBeInstanceOf(ServiceProvider::class);
    });

    it('implements DeferrableProvider contract', function (): void {
        expect(new CanonicalServiceProvider($this->app))->toBeInstanceOf(DeferrableProvider::class);
    });

    describe('register method', function (): void {
        it('merges config from package config file', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            $mergedConfig = config('canonical');

            expect($mergedConfig)->not()->toBeNull()
                ->and($mergedConfig)->toHaveKey('domain');
        });

        it('registers BaseUrlValidator as singleton', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            expect($this->app->bound(BaseUrlValidator::class))->toBeTrue();

            $instance1 = $this->app->make(BaseUrlValidator::class);
            $instance2 = $this->app->make(BaseUrlValidator::class);

            expect($instance1)->toBe($instance2);
        });

        it('registers CanonicalUrlBuilder as singleton', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            expect($this->app->bound(CanonicalUrlBuilder::class))->toBeTrue();

            $instance1 = $this->app->make(CanonicalUrlBuilder::class);
            $instance2 = $this->app->make(CanonicalUrlBuilder::class);

            expect($instance1)->toBe($instance2);
        });

        it('registers CanonicalConfig as singleton', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            expect($this->app->bound(CanonicalConfig::class))->toBeTrue();

            $instance1 = $this->app->make(CanonicalConfig::class);
            $instance2 = $this->app->make(CanonicalConfig::class);

            expect($instance1)->toBe($instance2);
        });

        it('registers CanonicalUrlGenerator as singleton', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            expect($this->app->bound(CanonicalUrlGenerator::class))->toBeTrue();

            $instance1 = $this->app->make(CanonicalUrlGenerator::class);
            $instance2 = $this->app->make(CanonicalUrlGenerator::class);

            expect($instance1)->toBe($instance2);
        });

        it('resolves CanonicalConfig with BaseUrlValidator dependency', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            $config = $this->app->make(CanonicalConfig::class);

            expect($config)->toBeInstanceOf(CanonicalConfig::class);
        });

        it('resolves CanonicalUrlGenerator with all dependencies', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            $generator = $this->app->make(CanonicalUrlGenerator::class);

            expect($generator)->toBeInstanceOf(CanonicalUrlGenerator::class);
        });
    });

    describe('boot method', function (): void {
        it('publishes config when running in console', function (): void {
            ServiceProvider::$publishes = [];

            (new CanonicalServiceProvider($this->app))->boot();

            $publishes = ServiceProvider::$publishes[CanonicalServiceProvider::class] ?? [];
            expect($publishes)->toHaveCount(1);

            $expectedConfigPath = Str::replace('/tests/Unit', '/src', __DIR__.'/../config/canonical.php');
            $expectedPublishPath = config_path('canonical.php');

            expect($publishes)->toHaveKey($expectedConfigPath)
                ->and($publishes[$expectedConfigPath])->toBe($expectedPublishPath);
        });

        it('publishes config with correct tag', function (): void {
            ServiceProvider::$publishGroups = [];

            (new CanonicalServiceProvider($this->app))->boot();

            $publishGroups = ServiceProvider::$publishGroups;

            expect($publishGroups)->toHaveKey('canonical-config');
        });
    });

    describe('provides method', function (): void {
        it('returns array containing all registered services', function (): void {
            $provides = (new CanonicalServiceProvider($this->app))->provides();

            expect($provides)->toBeArray()
                ->and($provides)->toContain(BaseUrlValidator::class)
                ->and($provides)->toContain(CanonicalConfig::class)
                ->and($provides)->toContain(CanonicalUrlBuilder::class)
                ->and($provides)->toContain(CanonicalUrlGenerator::class)
                ->and($provides)->toHaveCount(4);
        });
    });
});
