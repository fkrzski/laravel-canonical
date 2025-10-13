<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Services\CanonicalUrlBuilder;

mutates(CanonicalUrlBuilder::class);

describe('CanonicalUrlBuilder', function (): void {
    describe('build method', function (): void {
        it('builds URL from base and path', function (): void {
            $result = (new CanonicalUrlBuilder)->build('https://example.com', '/page');

            expect($result)->toBe('https://example.com/page');
        });

        it('removes trailing slash from base URL', function (): void {
            $result = (new CanonicalUrlBuilder)->build('https://example.com/', '/page');

            expect($result)->toBe('https://example.com/page');
        });

        it('removes leading slash from path', function (): void {
            $result = (new CanonicalUrlBuilder)->build('https://example.com', 'page');

            expect($result)->toBe('https://example.com/page');
        });

        it('handles both trailing and leading slashes', function (): void {
            $result = (new CanonicalUrlBuilder)->build('https://example.com/', '/page');

            expect($result)->toBe('https://example.com/page');
        });

        it('handles empty path', function (): void {
            $result = (new CanonicalUrlBuilder)->build('https://example.com', '');

            expect($result)->toBe('https://example.com');
        });

        it('handles root path', function (): void {
            $result = (new CanonicalUrlBuilder)->build('https://example.com', '/');

            expect($result)->toBe('https://example.com');
        });

        it('handles multiple path segments', function (): void {
            $result = (new CanonicalUrlBuilder)->build('https://example.com', '/path/to/page');

            expect($result)->toBe('https://example.com/path/to/page');
        });

        it('handles path with trailing slash', function (): void {
            $result = (new CanonicalUrlBuilder)->build('https://example.com', '/page/');

            expect($result)->toBe('https://example.com/page');
        });

        it('handles base URL with port', function (): void {
            $result = (new CanonicalUrlBuilder)->build('https://example.com:8080', '/page');

            expect($result)->toBe('https://example.com:8080/page');
        });

        it('handles base URL with subdomain', function (): void {
            $result = (new CanonicalUrlBuilder)->build('https://www.example.com', '/page');

            expect($result)->toBe('https://www.example.com/page');
        });
    });
});
