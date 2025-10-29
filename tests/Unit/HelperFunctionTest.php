<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlGeneratorInterface;
use Illuminate\Http\Request;

describe('canonical() Helper Function - Unit Tests', function (): void {
    beforeEach(function (): void {
        config(['canonical.domain' => 'https://example.com']);
    });

    it('exists as a global function', function (): void {
        expect(function_exists('canonical'))->toBeTrue();
    });

    describe('when called without arguments', function (): void {
        it('returns CanonicalUrlGeneratorInterface instance', function (): void {
            $result = canonical();

            expect($result)->toBeInstanceOf(CanonicalUrlGeneratorInterface::class);
        });

        it('returns same instance as container', function (): void {
            $generator = $this->app->make(CanonicalUrlGeneratorInterface::class);
            $helperResult = canonical();

            expect($helperResult)->toBe($generator);
        });

        it('can be used fluently to generate URL', function (): void {
            $result = canonical()->generate('/test-page');

            expect($result)->toBe('https://example.com/test-page');
        });

        it('can be used fluently without path', function (): void {
            $request = Request::create('https://test.com/current-page');
            $this->app->instance('request', $request);

            $result = canonical()->generate();

            expect($result)->toBe('https://example.com/current-page');
        });
    });

    describe('when called with path argument', function (): void {
        it('returns string URL', function (): void {
            expect(canonical('/test'))->toBeString();
        });

        it('generates canonical URL with provided path', function (): void {
            $result = canonical('/test-page');

            expect($result)->toBe('https://example.com/test-page');
        });

        it('handles empty string parameter', function (): void {
            $result = canonical('');

            expect($result)->toBe('https://example.com');
        });

        it('handles root path', function (): void {
            $result = canonical('/');

            expect($result)->toBe('https://example.com');
        });

        it('preserves query parameters', function (): void {
            $result = canonical('/test?foo=bar&baz=qux');

            expect($result)->toContain('foo=bar')
                ->and($result)->toContain('baz=qux');
        });

        it('handles path with trailing slash', function (): void {
            $result = canonical('/page/');

            expect($result)->toBe('https://example.com/page');
        });

        it('handles path without leading slash', function (): void {
            $result = canonical('page');

            expect($result)->toBe('https://example.com/page');
        });

        it('handles multiple path segments', function (): void {
            $result = canonical('/blog/category/post');

            expect($result)->toBe('https://example.com/blog/category/post');
        });
    });

    describe('compatibility with Blade usage', function (): void {
        it('can be used in link tag with path', function (): void {
            $url = canonical('/products/item');

            expect($url)->toBe('https://example.com/products/item');

            $html = sprintf('<link rel="canonical" href="%s" />', e($url));

            expect($html)->toBe('<link rel="canonical" href="https://example.com/products/item" />');
        });

        it('escapes HTML entities when used in Blade', function (): void {
            $url = canonical('/test?foo=bar&baz=qux');

            expect($url)->toContain('&');

            $escapedUrl = e($url);

            expect($escapedUrl)->toContain('&amp;')
                ->and($escapedUrl)->not->toContain('&baz');
        });

        it('can be used fluently in Blade', function (): void {
            $url = canonical()->generate('/test');

            expect($url)->toBe('https://example.com/test');

            $html = sprintf('<link rel="canonical" href="%s" />', e($url));

            expect($html)->toBe('<link rel="canonical" href="https://example.com/test" />');
        });
    });
});
