<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalUrlGenerator;
use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface;
use Fkrzski\LaravelCanonical\Validation\BaseUrlValidator;

mutates(CanonicalUrlGenerator::class);

describe('URL Generation - Basic Generation', function (): void {
    beforeEach(function (): void {
        config(['canonical.domain' => 'https://example.com']);

        $this->config = $this->app->make(CanonicalConfigInterface::class);
        $this->builder = $this->app->make(CanonicalUrlBuilderInterface::class);
        $this->generator = new CanonicalUrlGenerator($this->config, $this->builder);
    });

    it('generates canonical URL with provided path', function (): void {
        $url = $this->generator->generate('/test-page');

        expect($url)->toBe('https://example.com/test-page');
    });

    it('generates canonical URL with root path', function (): void {
        $url = $this->generator->generate('/');

        expect($url)->toBe('https://example.com');
    });

    it('generates canonical URL with multiple path segments', function (): void {
        $url = $this->generator->generate('/blog/post/123');

        expect($url)->toBe('https://example.com/blog/post/123');
    });

    it('generates canonical URL without leading slash', function (): void {
        $url = $this->generator->generate('test-page');

        expect($url)->toBe('https://example.com/test-page');
    });

    it('generates canonical URL with trailing slash', function (): void {
        $url = $this->generator->generate('/test-page/');

        expect($url)->toBe('https://example.com/test-page');
    });

    it('uses CanonicalConfig for base URL', function (): void {
        config(['canonical.domain' => 'https://different-domain.com']);

        $config = new CanonicalConfig(new BaseUrlValidator);
        $generator = new CanonicalUrlGenerator($config, $this->builder);

        $url = $generator->generate('/page');

        expect($url)->toBe('https://different-domain.com/page');
    });

    it('uses CanonicalUrlBuilder for URL construction', function (): void {
        $url = $this->generator->generate('/page');

        expect($url)->toBe('https://example.com/page');
    });
});
