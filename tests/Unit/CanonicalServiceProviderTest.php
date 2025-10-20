<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalServiceProvider;
use Fkrzski\LaravelCanonical\Contracts\BaseUrlValidatorInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlGeneratorInterface;
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

        it('registers BaseUrlValidatorInterface as singleton', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            expect($this->app->bound(BaseUrlValidatorInterface::class))->toBeTrue();

            $instance1 = $this->app->make(BaseUrlValidatorInterface::class);
            $instance2 = $this->app->make(BaseUrlValidatorInterface::class);

            expect($instance1)->toBe($instance2);
        });

        it('registers CanonicalUrlBuilderInterface as singleton', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            expect($this->app->bound(CanonicalUrlBuilderInterface::class))->toBeTrue();

            $instance1 = $this->app->make(CanonicalUrlBuilderInterface::class);
            $instance2 = $this->app->make(CanonicalUrlBuilderInterface::class);

            expect($instance1)->toBe($instance2);
        });

        it('registers CanonicalConfigInterface as singleton', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            expect($this->app->bound(CanonicalConfigInterface::class))->toBeTrue();

            $instance1 = $this->app->make(CanonicalConfigInterface::class);
            $instance2 = $this->app->make(CanonicalConfigInterface::class);

            expect($instance1)->toBe($instance2);
        });

        it('registers CanonicalUrlGeneratorInterface as singleton', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            expect($this->app->bound(CanonicalUrlGeneratorInterface::class))->toBeTrue();

            $instance1 = $this->app->make(CanonicalUrlGeneratorInterface::class);
            $instance2 = $this->app->make(CanonicalUrlGeneratorInterface::class);

            expect($instance1)->toBe($instance2);
        });

        it('resolves CanonicalConfigInterface with BaseUrlValidatorInterface dependency', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            $config = $this->app->make(CanonicalConfigInterface::class);

            expect($config)->toBeInstanceOf(CanonicalConfigInterface::class);
        });

        it('resolves CanonicalUrlGeneratorInterface with all dependencies', function (): void {
            (new CanonicalServiceProvider($this->app))->register();

            $generator = $this->app->make(CanonicalUrlGeneratorInterface::class);

            expect($generator)->toBeInstanceOf(CanonicalUrlGeneratorInterface::class);
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
                ->and($provides)->toContain(BaseUrlValidatorInterface::class)
                ->and($provides)->toContain(CanonicalConfigInterface::class)
                ->and($provides)->toContain(CanonicalUrlBuilderInterface::class)
                ->and($provides)->toContain(CanonicalUrlGeneratorInterface::class)
                ->and($provides)->toHaveCount(4);
        });
    });
});
