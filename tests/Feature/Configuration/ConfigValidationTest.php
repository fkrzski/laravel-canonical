<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Contracts\BaseUrlValidatorInterface;
use Fkrzski\LaravelCanonical\Exceptions\CanonicalConfigurationException;

mutates(CanonicalConfig::class);

describe('Configuration - Validation', function (): void {
    beforeEach(function (): void {
        $this->validator = $this->app->make(BaseUrlValidatorInterface::class);
    });

    it('throws exception when domain is empty', function (): void {
        config(['canonical.domain' => '']);

        expect(fn (): CanonicalConfig => new CanonicalConfig($this->validator))
            ->toThrow(CanonicalConfigurationException::class, 'Canonical domain is not set in config.');
    });

    it('throws exception when domain is not set', function (): void {
        config(['canonical.domain' => null]);

        expect(fn (): CanonicalConfig => new CanonicalConfig($this->validator))
            ->toThrow(InvalidArgumentException::class, 'Configuration value for key [canonical.domain] must be a string, NULL given.');
    });

    it('throws exception when domain is invalid URL', function (): void {
        config(['canonical.domain' => 'not-a-url']);

        expect(fn (): CanonicalConfig => new CanonicalConfig($this->validator))
            ->toThrow(CanonicalConfigurationException::class);
    });

    it('throws exception when domain has invalid scheme', function (): void {
        config(['canonical.domain' => 'ftp://example.com']);

        expect(fn (): CanonicalConfig => new CanonicalConfig($this->validator))
            ->toThrow(CanonicalConfigurationException::class, "Invalid URL scheme for 'ftp://example.com'. Only 'http' and 'https' schemes are allowed.");
    });

    it('throws exception when domain has no host', function (): void {
        config(['canonical.domain' => 'https://']);

        expect(fn (): CanonicalConfig => new CanonicalConfig($this->validator))
            ->toThrow(CanonicalConfigurationException::class, "Invalid URL format: 'https:'. Expected a valid URL.");
    });

    it('validates URL on construction', function (): void {
        config(['canonical.domain' => 'https://valid-domain.com']);

        $config = new CanonicalConfig($this->validator);

        expect($config->getBaseUrl())->toBe('https://valid-domain.com');
    });
});
