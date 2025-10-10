<?php

declare(strict_types=1);

describe('Canonical Config', function (): void {
    it('has domain key in config', function (): void {
        expect(config('canonical'))->toHaveKey('domain');
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
});
