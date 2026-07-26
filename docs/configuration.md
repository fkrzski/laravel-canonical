---
title: Configuration
description: Configure the canonical domain and trailing-slash handling — the published config file, the two environment variables, and how the domain is validated.
---

The package works with zero configuration once `CANONICAL_DOMAIN` (or `APP_URL`)
is set. Two values control everything: the **canonical domain** every URL is built
from, and whether **trailing slashes** are trimmed.

## The config file

The service provider merges its own `canonical` config, so the package runs
without publishing anything. Publish the file only when you want it in your repo
to override the defaults:

```bash
php artisan vendor:publish --tag="canonical-config"
```

This writes `config/canonical.php`:

```php
return [
    // The domain every canonical URL is built from. Falls back to APP_URL,
    // then http://localhost, when CANONICAL_DOMAIN is unset.
    'domain' => env('CANONICAL_DOMAIN', env('APP_URL', 'http://localhost')),

    // Trim trailing slashes from generated URLs (/blog/ → /blog). @since 1.1.0
    'trim_trailing_slash' => env('CANONICAL_TRIM_TRAILING_SLASH', true),
];
```

## The canonical domain

Set the domain in `.env`. Every generated URL is built from this value, never from
the host the request arrived on — the point of a canonical URL is to be
host-independent:

```dotenv
CANONICAL_DOMAIN=https://example.com
```

When `CANONICAL_DOMAIN` is unset the package falls back to `APP_URL`, and finally
to `http://localhost`. Set it explicitly in production so staging and `www` hosts
still emit the canonical apex URL.

## Trailing-slash handling

Trailing-slash handling is configurable **since 1.1.0**. By default, trailing
slashes are trimmed so `/blog/` and `/blog` collapse to one canonical URL. Flip it
off when your application treats the two as distinct routes:

```dotenv
# Trim trailing slashes (default)
CANONICAL_TRIM_TRAILING_SLASH=true

# Preserve the original trailing slash
CANONICAL_TRIM_TRAILING_SLASH=false
```

| Input | `true` (default) | `false` |
| --- | --- | --- |
| `/blog/` | `https://example.com/blog` | `https://example.com/blog/` |
| `/blog` | `https://example.com/blog` | `https://example.com/blog` |
| `/` | `https://example.com` | `https://example.com` |

Query parameters are preserved under both settings.

## Domain validation

The domain is validated the first time a URL is generated. It must be a
syntactically valid URL, use the `http` or `https` scheme, and include a host —
otherwise a `CanonicalConfigurationException` is thrown:

```php
CANONICAL_DOMAIN=https://example.com   // ✓ valid
CANONICAL_DOMAIN=http://localhost:8000 // ✓ valid
CANONICAL_DOMAIN=ftp://example.com     // ✗ scheme must be http or https
CANONICAL_DOMAIN=example.com           // ✗ missing scheme
CANONICAL_DOMAIN=                       // ✗ domain is not set
```

A trailing slash on the domain itself is stripped before validation, so
`https://example.com/` and `https://example.com` behave identically.

## Version compatibility

| Package version | PHP version | Laravel version |
| --- | --- | --- |
| 1.4.x+ | 8.4+ | 12.x, 13.x |
| 1.3.x+ | 8.4+ | 11.x, 12.x |
| 1.0.x – 1.2.x | 8.3+ | 11.x, 12.x |
