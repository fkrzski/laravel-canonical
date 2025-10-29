<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlGeneratorInterface;
use Fkrzski\LaravelCanonical\View\Components\Canonical;
use Illuminate\Http\Request;

mutates(Canonical::class);

describe('Blade Components - Canonical Component', function (): void {
    beforeEach(function (): void {
        config(['canonical.domain' => 'https://example.com']);
    });

    it('renders canonical link tag without path attribute', function (): void {
        $request = Request::create('https://test.com/current-page');
        $this->app->instance('request', $request);

        $view = $this->blade('<x-canonical />');

        expect($view)->toBe('<link rel="canonical" href="https://example.com/current-page" />');
    });

    it('renders canonical link tag with path attribute', function (): void {
        $view = $this->blade('<x-canonical path="/custom/page" />');

        expect($view)->toBe('<link rel="canonical" href="https://example.com/custom/page" />');
    });

    it('renders canonical link tag with dynamic path', function (): void {
        $view = $this->blade('<x-canonical :path="$path" />', ['path' => '/dynamic/page']);

        expect($view)->toBe('<link rel="canonical" href="https://example.com/dynamic/page" />');
    });

    it('handles null path attribute', function (): void {
        $request = Request::create('https://test.com/page');
        $this->app->instance('request', $request);

        $view = $this->blade('<x-canonical :path="null" />');

        expect($view)->toBe('<link rel="canonical" href="https://example.com/page" />');
    });

    it('handles empty string path', function (): void {
        $view = $this->blade('<x-canonical path="" />');

        expect($view)->toBe('<link rel="canonical" href="https://example.com" />');
    });

    it('handles root path', function (): void {
        $view = $this->blade('<x-canonical path="/" />');

        expect($view)->toBe('<link rel="canonical" href="https://example.com" />');
    });

    it('escapes HTML entities in URL', function (): void {
        $view = $this->blade('<x-canonical path="/test?foo=bar&baz=qux" />');

        expect($view)->toContain('&amp;')
            ->and($view)->not->toContain('&baz');
    });

    it('handles path with trailing slash', function (): void {
        $view = $this->blade('<x-canonical path="/page/" />');

        expect($view)->toBe('<link rel="canonical" href="https://example.com/page" />');
    });

    it('handles path without leading slash', function (): void {
        $view = $this->blade('<x-canonical path="page" />');

        expect($view)->toBe('<link rel="canonical" href="https://example.com/page" />');
    });

    it('generates single line output', function (): void {
        $view = $this->blade('<x-canonical path="/test" />');

        expect($view)->not->toContain("\n")
            ->and($view)->not->toContain("\r");
    });

    it('uses CanonicalUrlGeneratorInterface', function (): void {
        $generator = $this->app->make(CanonicalUrlGeneratorInterface::class);

        expect($generator)->toBeInstanceOf(CanonicalUrlGeneratorInterface::class);

        $view = $this->blade('<x-canonical path="/test" />');

        expect($view)->toContain('https://example.com/test');
    });

    it('handles multiple path segments', function (): void {
        $view = $this->blade('<x-canonical path="/blog/category/post" />');

        expect($view)->toBe('<link rel="canonical" href="https://example.com/blog/category/post" />');
    });

    it('preserves query parameters', function (): void {
        $view = $this->blade('<x-canonical path="/search?q=laravel&page=2" />');

        expect($view)->toContain('q=laravel')
            ->and($view)->toContain('page=2');
    });
});
