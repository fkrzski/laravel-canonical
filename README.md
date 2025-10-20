# Laravel Canonical URLs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fkrzski/laravel-canonical.svg?style=for-the-badge)](https://packagist.org/packages/fkrzski/laravel-canonical)
[![Tests](https://img.shields.io/github/actions/workflow/status/fkrzski/laravel-canonical/tests.yml?branch=main&label=tests&style=for-the-badge)](https://github.com/fkrzski/laravel-canonical/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/fkrzski/laravel-canonical.svg?style=for-the-badge)](https://packagist.org/packages/fkrzski/laravel-canonical)

A lightweight Laravel package for generating canonical URLs to prevent duplicate content issues and improve SEO. Automatically normalizes URLs by removing trailing slashes while preserving query parameters.

## Installation

Install via Composer:

```bash
composer require fkrzski/laravel-canonical
```

The package will auto-register via Laravel's package discovery.

## Configuration

Publish the configuration file (optional):

```bash
php artisan vendor:publish --tag="canonical-config"
```

Set your canonical domain in `.env`:

```env
CANONICAL_DOMAIN=https://example.com
```

If not set, it falls back to `APP_URL`.

## Usage

### In Blade Templates

Generate canonical URLs in your views:

```blade
<head>
    <link rel="canonical" href="{{ Canonical::generate() }}">
</head>
```

### Generate for Specific Paths

```php
use Fkrzski\LaravelCanonical\Facades\Canonical;

// Current request URI
Canonical::generate(); // https://example.com/blog/post

// Override with a custom path
Canonical::generate('/products/item'); // https://example.com/products/item

// Current request with query parameters (preserved)
// Request: /search?q=laravel&page=2
Canonical::generate(); // https://example.com/search?q=laravel&page=2

// Override path even when on different URL
// Current: /old-url, Generate: /new-canonical-url
Canonical::generate('/new-canonical-url'); // https://example.com/new-canonical-url
```

### URL Normalization

The package automatically:
- Removes trailing slashes: `/blog/` → `/blog`
- Preserves query parameters: `/search?q=test` stays intact
- Normalizes root URL: `/` → `https://example.com`

## Use Cases

**Prevent duplicate content penalties:**
```blade
{{-- Both /blog and /blog/ point to same canonical --}}
<link rel="canonical" href="{{ Canonical::generate() }}">
```

**Multi-domain environments:**
```php
// config/canonical.php
return [
    'domain' => env('CANONICAL_DOMAIN', config('app.url')),
];
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Filip Krzyżanowski](https://github.com/fkrzski)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
