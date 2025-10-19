<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalUrlGenerator;
use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Services\CanonicalUrlBuilder;
use Fkrzski\LaravelCanonical\Validation\BaseUrlValidator;
use Illuminate\Http\Request;

mutates(CanonicalUrlGenerator::class);

describe('CanonicalUrlGenerator', function (): void {
    beforeEach(function (): void {
        config(['canonical.domain' => 'https://example.com']);

        $this->config = new CanonicalConfig(new BaseUrlValidator);
        $this->builder = new CanonicalUrlBuilder;
        $this->generator = new CanonicalUrlGenerator($this->config, $this->builder);
    });

    describe('generate method', function (): void {
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

        it('uses current request path when no path provided', function (): void {
            $request = Request::create('https://test.com/current-page');
            $this->app->instance('request', $request);

            $url = $this->generator->generate();

            expect($url)->toBe('https://example.com/current-page');
        });

        it('uses root path when request returns root', function (): void {
            $request = Request::create('https://test.com/');
            $this->app->instance('request', $request);

            $url = $this->generator->generate();

            expect($url)->toBe('https://example.com');
        });

        it('handles request with query parameters by using path wit query parameters', function (): void {
            $request = Request::create('https://test.com/page?foo=bar&baz=qux');
            $this->app->instance('request', $request);

            $url = $this->generator->generate();

            expect($url)->toBe('https://example.com/page?foo=bar&baz=qux');
        });

        it('normalizes request path that already starts with slash', function (): void {
            $request = Request::create('https://test.com//double-slash');
            $this->app->instance('request', $request);

            $url = $this->generator->generate();

            expect($url)->toBe('https://example.com/double-slash');
        });

        it('handles request path without leading slash correctly', function (): void {
            $request = new Request;
            $request->server->set('REQUEST_URI', 'no-leading-slash');
            $this->app->instance('request', $request);

            $url = $this->generator->generate();

            expect($url)->toBe('https://example.com/no-leading-slash');
        });

        it('handles nested paths from request', function (): void {
            $request = Request::create('https://test.com/blog/category/post');
            $this->app->instance('request', $request);

            $url = $this->generator->generate();

            expect($url)->toBe('https://example.com/blog/category/post');
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
});
