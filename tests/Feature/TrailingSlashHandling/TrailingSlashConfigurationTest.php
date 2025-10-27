<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Contracts\BaseUrlValidatorInterface;

mutates(CanonicalConfig::class);

describe('Trailing Slash Handling - Configuration', function (): void {
    beforeEach(function (): void {
        $this->validator = $this->app->make(BaseUrlValidatorInterface::class);
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
