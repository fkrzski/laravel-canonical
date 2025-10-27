<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Services\CanonicalUrlBuilder;

mutates(CanonicalUrlBuilder::class);

describe('CanonicalUrlBuilder', function (): void {
    describe('build method with trim_trailing_slash enabled', function (): void {
        beforeEach(function (): void {
            $this->config = Mockery::mock(CanonicalConfigInterface::class);
            $this->config->shouldReceive('shouldTrimTrailingSlash')->andReturn(true);
            $this->builder = new CanonicalUrlBuilder($this->config);
        });

        it('builds URL from base and path', function (): void {
            $result = $this->builder->build('https://example.com', '/page');

            expect($result)->toBe('https://example.com/page');
        });

        it('removes trailing slash from base URL', function (): void {
            $result = $this->builder->build('https://example.com/', '/page');

            expect($result)->toBe('https://example.com/page');
        });

        it('removes leading slash from path', function (): void {
            $result = $this->builder->build('https://example.com', 'page');

            expect($result)->toBe('https://example.com/page');
        });

        it('handles both trailing and leading slashes', function (): void {
            $result = $this->builder->build('https://example.com/', '/page');

            expect($result)->toBe('https://example.com/page');
        });

        it('handles empty path', function (): void {
            $result = $this->builder->build('https://example.com', '');

            expect($result)->toBe('https://example.com');
        });

        it('handles root path', function (): void {
            $result = $this->builder->build('https://example.com', '/');

            expect($result)->toBe('https://example.com');
        });

        it('handles multiple path segments', function (): void {
            $result = $this->builder->build('https://example.com', '/path/to/page');

            expect($result)->toBe('https://example.com/path/to/page');
        });

        it('handles path with trailing slash', function (): void {
            $result = $this->builder->build('https://example.com', '/page/');

            expect($result)->toBe('https://example.com/page');
        });

        it('handles base URL with port', function (): void {
            $result = $this->builder->build('https://example.com:8080', '/page');

            expect($result)->toBe('https://example.com:8080/page');
        });

        it('handles base URL with subdomain', function (): void {
            $result = $this->builder->build('https://www.example.com', '/page');

            expect($result)->toBe('https://www.example.com/page');
        });

        it('handles base URL with trailing slash and empty path', function (): void {
            $result = $this->builder->build('https://example.com/', '');

            expect($result)->toBe('https://example.com');
        });

        it('handles base URL with trailing slash and path without trailing slash', function (): void {
            $result = $this->builder->build('https://example.com/', 'page');

            expect($result)->toBe('https://example.com/page');
        });
    });

    describe('build method with trim_trailing_slash disabled', function (): void {
        beforeEach(function (): void {
            $this->config = Mockery::mock(CanonicalConfigInterface::class);
            $this->config->shouldReceive('shouldTrimTrailingSlash')->andReturn(false);
            $this->builder = new CanonicalUrlBuilder($this->config);
        });

        it('preserves trailing slash in path', function (): void {
            $result = $this->builder->build('https://example.com', '/page/');

            expect($result)->toBe('https://example.com/page/');
        });

        it('removes leading slash but keeps trailing', function (): void {
            $result = $this->builder->build('https://example.com', 'page/');

            expect($result)->toBe('https://example.com/page/');
        });

        it('handles path without trailing slash', function (): void {
            $result = $this->builder->build('https://example.com', '/page');

            expect($result)->toBe('https://example.com/page');
        });

        it('deduplicate multiple trailing slashes', function (): void {
            $result = $this->builder->build('https://example.com', '/page//');

            expect($result)->toBe('https://example.com/page/');
        });

        it('handles empty path', function (): void {
            $result = $this->builder->build('https://example.com', '');

            expect($result)->toBe('https://example.com');
        });

        it('handles root path', function (): void {
            $result = $this->builder->build('https://example.com', '/');

            expect($result)->toBe('https://example.com/');
        });

        it('preserves trailing slash in nested paths', function (): void {
            $result = $this->builder->build('https://example.com', '/path/to/page/');

            expect($result)->toBe('https://example.com/path/to/page/');
        });

        it('handles base URL with trailing slash and empty path', function (): void {
            $result = $this->builder->build('https://example.com/', '');

            expect($result)->toBe('https://example.com/');
        });

        it('handles base URL with trailing slash and path without trailing slash', function (): void {
            $result = $this->builder->build('https://example.com/', 'page');

            expect($result)->toBe('https://example.com/page');
        });

        it('handles base URL with trailing slash and path with trailing slash', function (): void {
            $result = $this->builder->build('https://example.com/', 'page/');

            expect($result)->toBe('https://example.com/page/');
        });
    });
});
