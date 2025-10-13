<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Exceptions\CanonicalConfigurationException;
use Fkrzski\LaravelCanonical\Validation\BaseUrlValidator;

mutates(BaseUrlValidator::class);

describe('BaseUrlValidator', function (): void {
    describe('validate method', function (): void {
        it('validates correct http URL', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('http://example.com'))->not->toThrow(Exception::class);
        });

        it('validates correct https URL', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('https://example.com'))->not->toThrow(Exception::class);
        });

        it('validates URL with subdomain', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('https://www.example.com'))->not->toThrow(Exception::class);
        });

        it('validates URL with port', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('https://example.com:8080'))->not->toThrow(Exception::class);
        });

        it('validates URL with path', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('https://example.com/path'))->not->toThrow(Exception::class);
        });

        it('throws exception for invalid URL format', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('not-a-url'))
                ->toThrow(CanonicalConfigurationException::class, "Invalid URL format: 'not-a-url'. Expected a valid URL.");
        });

        it('throws exception for URL without scheme', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('example.com'))
                ->toThrow(CanonicalConfigurationException::class, "Invalid URL format: 'example.com'. Expected a valid URL.");
        });

        it('throws exception for ftp scheme', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('ftp://example.com'))
                ->toThrow(CanonicalConfigurationException::class, "Invalid URL scheme for 'ftp://example.com'. Only 'http' and 'https' schemes are allowed.");
        });

        it('throws exception for file scheme', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('file:///path/to/file'))
                ->toThrow(CanonicalConfigurationException::class, "Invalid URL scheme for 'file:///path/to/file'. Only 'http' and 'https' schemes are allowed.");
        });

        it('throws exception for URL without host', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('http://'))
                ->toThrow(CanonicalConfigurationException::class, "Invalid URL format: 'http://'. Expected a valid URL.");
        });

        it('throws exception for empty string', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate(''))
                ->toThrow(CanonicalConfigurationException::class, "Invalid URL format: ''. Expected a valid URL.");
        });

        it('throws exception for URL with only scheme', function (): void {
            expect(fn () => (new BaseUrlValidator)->validate('https://'))
                ->toThrow(CanonicalConfigurationException::class, "Invalid URL format: 'https://'. Expected a valid URL");
        });
    });
});
