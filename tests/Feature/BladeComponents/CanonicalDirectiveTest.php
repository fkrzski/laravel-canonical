<?php

declare(strict_types=1);

use Illuminate\Http\Request;

describe('Blade Components - Canonical Directive', function (): void {
    beforeEach(function (): void {
        config(['canonical.domain' => 'https://example.com']);
    });

    it('renders canonical link tag without parameters', function (): void {
        $request = Request::create('https://test.com/current-page');
        $this->app->instance('request', $request);

        $view = $this->blade('@canonical');

        expect($view)->toBe('<link rel="canonical" href="https://example.com/current-page" />');
    });

    it('renders canonical link tag with path parameter', function (): void {
        $view = $this->blade("@canonical('/custom/page')");

        expect($view)->toBe('<link rel="canonical" href="https://example.com/custom/page" />');
    });

    it('renders canonical link tag with variable', function (): void {
        $view = $this->blade('@canonical($path)', ['path' => '/dynamic/page']);

        expect($view)->toBe('<link rel="canonical" href="https://example.com/dynamic/page" />');
    });

    it('handles double-quoted string parameter', function (): void {
        $view = $this->blade('@canonical("/test/page")');

        expect($view)->toBe('<link rel="canonical" href="https://example.com/test/page" />');
    });

    it('handles single-quoted string parameter', function (): void {
        $view = $this->blade("@canonical('/test/page')");

        expect($view)->toBe('<link rel="canonical" href="https://example.com/test/page" />');
    });

    it('handles empty parentheses', function (): void {
        $request = Request::create('https://test.com/page');
        $this->app->instance('request', $request);

        $view = $this->blade('@canonical()');

        expect($view)->toBe('<link rel="canonical" href="https://example.com/page" />');
    });

    it('handles root path', function (): void {
        $view = $this->blade("@canonical('/')");

        expect($view)->toBe('<link rel="canonical" href="https://example.com" />');
    });

    it('escapes HTML entities in URL', function (): void {
        $view = $this->blade("@canonical('/test?foo=bar&baz=qux')");

        expect($view)->toContain('&amp;')
            ->and($view)->not->toContain('&baz');
    });

    it('generates single line output', function (): void {
        $view = $this->blade("@canonical('/test')");

        expect($view)->not->toContain("\n")
            ->and($view)->not->toContain("\r");
    });

    it('handles path with trailing slash', function (): void {
        $view = $this->blade("@canonical('/page/')");

        expect($view)->toBe('<link rel="canonical" href="https://example.com/page" />');
    });

    it('handles multiple path segments', function (): void {
        $view = $this->blade("@canonical('/blog/category/post')");

        expect($view)->toBe('<link rel="canonical" href="https://example.com/blog/category/post" />');
    });

    it('preserves query parameters', function (): void {
        $view = $this->blade("@canonical('/search?q=laravel&page=2')");

        expect($view)->toContain('q=laravel')
            ->and($view)->toContain('page=2');
    });

    it('can be used multiple times in same view', function (): void {
        $view = $this->blade(<<<'BLADE'
            @canonical('/page1')
            @canonical('/page2')
        BLADE);

        expect($view)->toContain('href="https://example.com/page1"')
            ->and($view)->toContain('href="https://example.com/page2"');
    });
});
