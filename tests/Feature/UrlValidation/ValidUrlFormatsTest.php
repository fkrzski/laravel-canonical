<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Validation\BaseUrlValidator;

mutates(BaseUrlValidator::class);

describe('URL Validation - Valid Formats', function (): void {
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
});
