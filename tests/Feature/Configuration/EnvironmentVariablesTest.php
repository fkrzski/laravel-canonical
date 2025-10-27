<?php

declare(strict_types=1);

describe('Configuration - Environment Variables', function (): void {
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
});
