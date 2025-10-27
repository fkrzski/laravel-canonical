<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

mutates(CanonicalServiceProvider::class);

describe('Laravel Integration - Service Provider', function (): void {
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
    });

    describe('boot method', function (): void {
        it('publishes config when running in console', function (): void {
            ServiceProvider::$publishes = [];

            (new CanonicalServiceProvider($this->app))->boot();

            $publishes = ServiceProvider::$publishes[CanonicalServiceProvider::class] ?? [];
            expect($publishes)->toHaveCount(1);

            $expectedConfigPath = Str::replace('/tests/Feature/LaravelIntegration', '/src', __DIR__.'/../config/canonical.php');
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
                ->and($provides)->toContain(Fkrzski\LaravelCanonical\Contracts\BaseUrlValidatorInterface::class)
                ->and($provides)->toContain(Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface::class)
                ->and($provides)->toContain(Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface::class)
                ->and($provides)->toContain(Fkrzski\LaravelCanonical\Contracts\CanonicalUrlGeneratorInterface::class)
                ->and($provides)->toHaveCount(4);
        });
    });
});
