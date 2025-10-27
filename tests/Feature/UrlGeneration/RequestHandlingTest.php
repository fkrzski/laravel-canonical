<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalUrlGenerator;
use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface;
use Illuminate\Http\Request;

mutates(CanonicalUrlGenerator::class);

describe('URL Generation - Request Handling', function (): void {
    beforeEach(function (): void {
        config(['canonical.domain' => 'https://example.com']);

        $this->config = $this->app->make(CanonicalConfigInterface::class);
        $this->builder = $this->app->make(CanonicalUrlBuilderInterface::class);
        $this->generator = new CanonicalUrlGenerator($this->config, $this->builder);
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
});
