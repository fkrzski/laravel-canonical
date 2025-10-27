<?php

declare(strict_types=1);

describe('Configuration - Config File', function (): void {
    it('has domain key in config', function (): void {
        expect(config('canonical'))->toHaveKey('domain');
    });

    it('has trim_trailing_slash key in config', function (): void {
        expect(config('canonical'))->toHaveKey('trim_trailing_slash');
    });

    it('falls back to http://localhost when neither env variable is set', function (): void {
        putenv('CANONICAL_DOMAIN');
        putenv('APP_URL');
        $this->app['config']->set('canonical.domain', env('CANONICAL_DOMAIN', env('APP_URL', 'http://localhost')));

        expect(config('canonical.domain'))->toBe('http://localhost');
    });

    it('defaults trim_trailing_slash to true when env variable is not set', function (): void {
        putenv('CANONICAL_TRIM_TRAILING_SLASH');
        $this->app['config']->set('canonical.trim_trailing_slash', env('CANONICAL_TRIM_TRAILING_SLASH', true));

        expect(config('canonical.trim_trailing_slash'))->toBe(true);
    });
});
