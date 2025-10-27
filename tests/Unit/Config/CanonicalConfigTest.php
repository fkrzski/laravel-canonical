<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Contracts\BaseUrlValidatorInterface;
use Fkrzski\LaravelCanonical\Exceptions\CanonicalConfigurationException;

mutates(CanonicalConfig::class);

describe('CanonicalConfig', function (): void {
    describe('config file', function (): void {
        it('has domain key in config', function (): void {
            expect(config('canonical'))->toHaveKey('domain');
        });

        it('has trim_trailing_slash key in config', function (): void {
            expect(config('canonical'))->toHaveKey('trim_trailing_slash');
        });

        it('uses CANONICAL_DOMAIN env variable when set', function (): void {
            putenv('CANONICAL_DOMAIN=https://example.com');
            $this->app['config']->set('canonical.domain', env('CANONICAL_DOMAIN', env('APP_URL', 'http://localhost')));

            expect(config('canonical.domain'))->toBe('https://example.com');

            putenv('CANONICAL_DOMAIN');
        });

        it('falls back to APP_URL when CANONICAL_DOMAIN is not set', function (): void {
            putenv('CANONICAL_DOMAIN');
            putenv('APP_URL=https://app-url.com');
            $this->app['config']->set('canonical.domain', env('CANONICAL_DOMAIN', env('APP_URL', 'http://localhost')));

            expect(config('canonical.domain'))->toBe('https://app-url.com');

            putenv('APP_URL');
        });

        it('falls back to http://localhost when neither env variable is set', function (): void {
            putenv('CANONICAL_DOMAIN');
            putenv('APP_URL');
            $this->app['config']->set('canonical.domain', env('CANONICAL_DOMAIN', env('APP_URL', 'http://localhost')));

            expect(config('canonical.domain'))->toBe('http://localhost');
        });

        it('uses CANONICAL_TRIM_TRAILING_SLASH env variable when set to true', function (): void {
            putenv('CANONICAL_TRIM_TRAILING_SLASH=true');
            $this->app['config']->set('canonical.trim_trailing_slash', env('CANONICAL_TRIM_TRAILING_SLASH', true));

            expect(config('canonical.trim_trailing_slash'))->toBe(true);

            putenv('CANONICAL_TRIM_TRAILING_SLASH');
        });

        it('uses CANONICAL_TRIM_TRAILING_SLASH env variable when set to false', function (): void {
            putenv('CANONICAL_TRIM_TRAILING_SLASH=false');
            $this->app['config']->set('canonical.trim_trailing_slash', env('CANONICAL_TRIM_TRAILING_SLASH', true));

            expect(config('canonical.trim_trailing_slash'))->toBe(false);

            putenv('CANONICAL_TRIM_TRAILING_SLASH');
        });

        it('defaults trim_trailing_slash to true when env variable is not set', function (): void {
            putenv('CANONICAL_TRIM_TRAILING_SLASH');
            $this->app['config']->set('canonical.trim_trailing_slash', env('CANONICAL_TRIM_TRAILING_SLASH', true));

            expect(config('canonical.trim_trailing_slash'))->toBe(true);
        });
    });

    describe('CanonicalConfig class', function (): void {
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

        it('is readonly class', function (): void {
            $reflection = new ReflectionClass(CanonicalConfig::class);

            expect($reflection->isReadOnly())->toBeTrue();
        });

        it('validates URL on construction', function (): void {
            config(['canonical.domain' => 'https://valid-domain.com']);

            $config = new CanonicalConfig($this->validator);

            expect($config->getBaseUrl())->toBe('https://valid-domain.com');
        });

        it('returns true for shouldTrimTrailingSlash when config is true', function (): void {
            config([
                'canonical.domain' => 'https://example.com',
                'canonical.trim_trailing_slash' => true,
            ]);

            $config = new CanonicalConfig($this->validator);

            expect($config->shouldTrimTrailingSlash())->toBeTrue();
        });

        it('returns false for shouldTrimTrailingSlash when config is false', function (): void {
            config([
                'canonical.domain' => 'https://example.com',
                'canonical.trim_trailing_slash' => false,
            ]);

            $config = new CanonicalConfig($this->validator);

            expect($config->shouldTrimTrailingSlash())->toBeFalse();
        });

        it('defaults to true for shouldTrimTrailingSlash when not configured', function (): void {
            config([
                'canonical.domain' => 'https://example.com',
            ]);

            $config = new CanonicalConfig($this->validator);

            expect($config->shouldTrimTrailingSlash())->toBeTrue();
        });
    });
});
