<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalUrlGenerator;
use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface;
use Illuminate\Http\Request;

mutates(CanonicalUrlGenerator::class);

describe('URL Generation - Query Parameters', function (): void {
    beforeEach(function (): void {
        config(['canonical.domain' => 'https://example.com']);

        $this->config = $this->app->make(CanonicalConfigInterface::class);
        $this->builder = $this->app->make(CanonicalUrlBuilderInterface::class);
        $this->generator = new CanonicalUrlGenerator($this->config, $this->builder);
    });

    it('handles request with query parameters by using path wit query parameters', function (): void {
        $request = Request::create('https://test.com/page?foo=bar&baz=qux');
        $this->app->instance('request', $request);

        $url = $this->generator->generate();

        expect($url)->toBe('https://example.com/page?foo=bar&baz=qux');
    });
});
