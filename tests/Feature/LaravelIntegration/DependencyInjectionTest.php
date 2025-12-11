<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalServiceProvider;
use Fkrzski\LaravelCanonical\Contracts\BaseUrlValidatorInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlGeneratorInterface;

mutates(CanonicalServiceProvider::class);

describe('Laravel Integration - Dependency Injection', function (): void {
    it('registers BaseUrlValidatorInterface as singleton', function (): void {
        new CanonicalServiceProvider($this->app)->register();

        expect($this->app->bound(BaseUrlValidatorInterface::class))->toBeTrue();

        $instance1 = $this->app->make(BaseUrlValidatorInterface::class);
        $instance2 = $this->app->make(BaseUrlValidatorInterface::class);

        expect($instance1)->toBe($instance2);
    });

    it('registers CanonicalUrlBuilderInterface as singleton', function (): void {
        new CanonicalServiceProvider($this->app)->register();

        expect($this->app->bound(CanonicalUrlBuilderInterface::class))->toBeTrue();

        $instance1 = $this->app->make(CanonicalUrlBuilderInterface::class);
        $instance2 = $this->app->make(CanonicalUrlBuilderInterface::class);

        expect($instance1)->toBe($instance2);
    });

    it('registers CanonicalConfigInterface as singleton', function (): void {
        new CanonicalServiceProvider($this->app)->register();

        expect($this->app->bound(CanonicalConfigInterface::class))->toBeTrue();

        $instance1 = $this->app->make(CanonicalConfigInterface::class);
        $instance2 = $this->app->make(CanonicalConfigInterface::class);

        expect($instance1)->toBe($instance2);
    });

    it('registers CanonicalUrlGeneratorInterface as singleton', function (): void {
        new CanonicalServiceProvider($this->app)->register();

        expect($this->app->bound(CanonicalUrlGeneratorInterface::class))->toBeTrue();

        $instance1 = $this->app->make(CanonicalUrlGeneratorInterface::class);
        $instance2 = $this->app->make(CanonicalUrlGeneratorInterface::class);

        expect($instance1)->toBe($instance2);
    });

    it('resolves CanonicalConfigInterface with BaseUrlValidatorInterface dependency', function (): void {
        new CanonicalServiceProvider($this->app)->register();

        $config = $this->app->make(CanonicalConfigInterface::class);

        expect($config)->toBeInstanceOf(CanonicalConfigInterface::class);
    });

    it('resolves CanonicalUrlGeneratorInterface with all dependencies', function (): void {
        new CanonicalServiceProvider($this->app)->register();

        $generator = $this->app->make(CanonicalUrlGeneratorInterface::class);

        expect($generator)->toBeInstanceOf(CanonicalUrlGeneratorInterface::class);
    });
});
