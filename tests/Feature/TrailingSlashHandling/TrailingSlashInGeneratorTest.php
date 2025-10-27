<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalUrlGenerator;
use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface;
use Fkrzski\LaravelCanonical\Validation\BaseUrlValidator;
use Illuminate\Http\Request;

mutates(CanonicalUrlGenerator::class);

describe('Trailing Slash Handling - URL Generator', function (): void {
    describe('with trim_trailing_slash enabled', function (): void {
        it('removes trailing slash when trim_trailing_slash is true', function (): void {
            config([
                'canonical.domain' => 'https://example.com',
                'canonical.trim_trailing_slash' => true,
            ]);

            $config = new CanonicalConfig(new BaseUrlValidator);
            $builder = $this->app->make(CanonicalUrlBuilderInterface::class);
            $generator = new CanonicalUrlGenerator($config, $builder);

            $url = $generator->generate('/page/');

            expect($url)->toBe('https://example.com/page');
        });
    });

    describe('with trim_trailing_slash disabled', function (): void {
        it('preserves trailing slash when trim_trailing_slash is false', function (): void {
            $this->app->forgetInstance(CanonicalConfigInterface::class);
            $this->app->forgetInstance(CanonicalUrlBuilderInterface::class);

            config([
                'canonical.domain' => 'https://example.com',
                'canonical.trim_trailing_slash' => false,
            ]);

            $config = new CanonicalConfig(new BaseUrlValidator);
            $builder = $this->app->make(CanonicalUrlBuilderInterface::class);
            $generator = new CanonicalUrlGenerator($config, $builder);

            $url = $generator->generate('/page/');

            expect($url)->toBe('https://example.com/page/');
        });

        it('uses current request with preserved trailing slash', function (): void {
            $this->app->forgetInstance(CanonicalConfigInterface::class);
            $this->app->forgetInstance(CanonicalUrlBuilderInterface::class);

            config([
                'canonical.domain' => 'https://example.com',
                'canonical.trim_trailing_slash' => false,
            ]);

            $request = Request::create('https://test.com/current-page/');
            $this->app->instance('request', $request);

            $config = new CanonicalConfig(new BaseUrlValidator);
            $builder = $this->app->make(CanonicalUrlBuilderInterface::class);
            $generator = new CanonicalUrlGenerator($config, $builder);

            $url = $generator->generate();

            expect($url)->toBe('https://example.com/current-page/');
        });

        it('uses current request with preserved trailing slash for only domain', function (): void {
            $this->app->forgetInstance(CanonicalConfigInterface::class);
            $this->app->forgetInstance(CanonicalUrlBuilderInterface::class);

            config([
                'canonical.domain' => 'https://example.com',
                'canonical.trim_trailing_slash' => false,
            ]);

            $request = Request::create('https://test.com/');
            $this->app->instance('request', $request);

            $config = new CanonicalConfig(new BaseUrlValidator);
            $builder = $this->app->make(CanonicalUrlBuilderInterface::class);
            $generator = new CanonicalUrlGenerator($config, $builder);

            $url = $generator->generate();

            expect($url)->toBe('https://example.com/');
        });

        it('deduplicates trailing slash and preserves it for domain', function (): void {
            $this->app->forgetInstance(CanonicalConfigInterface::class);
            $this->app->forgetInstance(CanonicalUrlBuilderInterface::class);

            config([
                'canonical.domain' => 'https://example.com',
                'canonical.trim_trailing_slash' => false,
            ]);

            $request = Request::create('https://test.com////');
            $this->app->instance('request', $request);

            $config = new CanonicalConfig(new BaseUrlValidator);
            $builder = $this->app->make(CanonicalUrlBuilderInterface::class);
            $generator = new CanonicalUrlGenerator($config, $builder);

            $url = $generator->generate();

            expect($url)->toBe('https://example.com/');
        });

        it('deduplicates trailing slash and preserves it for path', function (): void {
            $this->app->forgetInstance(CanonicalConfigInterface::class);
            $this->app->forgetInstance(CanonicalUrlBuilderInterface::class);

            config([
                'canonical.domain' => 'https://example.com',
                'canonical.trim_trailing_slash' => false,
            ]);

            $request = Request::create('https://test.com/current-page///');
            $this->app->instance('request', $request);

            $config = new CanonicalConfig(new BaseUrlValidator);
            $builder = $this->app->make(CanonicalUrlBuilderInterface::class);
            $generator = new CanonicalUrlGenerator($config, $builder);

            $url = $generator->generate();

            expect($url)->toBe('https://example.com/current-page/');
        });
    });
});
