<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Contracts\BaseUrlValidatorInterface;

mutates(CanonicalConfig::class);

describe('CanonicalConfig - Unit Tests', function (): void {
    beforeEach(function (): void {
        $this->validator = $this->app->make(BaseUrlValidatorInterface::class);
    });

    it('creates instance with valid URL', function (): void {
        config(['canonical.domain' => 'https://example.com']);

        $config = new CanonicalConfig($this->validator);

        expect($config)->toBeInstanceOf(CanonicalConfig::class);
    });

    it('returns base URL without trailing slash', function (): void {
        config(['canonical.domain' => 'https://example.com/']);

        $config = new CanonicalConfig($this->validator);

        expect($config->getBaseUrl())->toBe('https://example.com');
    });

    it('returns base URL when already without trailing slash', function (): void {
        config(['canonical.domain' => 'https://example.com']);

        $config = new CanonicalConfig($this->validator);

        expect($config->getBaseUrl())->toBe('https://example.com');
    });

    it('is readonly class', function (): void {
        $reflection = new ReflectionClass(CanonicalConfig::class);

        expect($reflection->isReadOnly())->toBeTrue();
    });
});
