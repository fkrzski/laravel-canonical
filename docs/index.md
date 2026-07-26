---
title: laravel-canonical
description: Laravel package for canonical URLs that prevents duplicate-content SEO issues — a Canonical facade, a canonical() helper, a Blade component, and a directive.
repository: https://github.com/fkrzski/laravel-canonical
packagist: fkrzski/laravel-canonical
status: stable
---

A lightweight Laravel package for generating canonical URLs to prevent
duplicate-content issues and improve SEO. It normalises every URL against one
configured domain — trimming trailing slashes while preserving query parameters —
and exposes that as a `Canonical` facade, a `canonical()` helper, a Blade
component, and a Blade directive.

## Why laravel-canonical

- **One canonical domain** — every generated URL is built from a single configured domain, so staging, `www`, and apex hosts all resolve to the same canonical link.
- **Deterministic normalisation** — trailing slashes are trimmed by default and query parameters are preserved, so `/blog/` and `/blog` collapse to one canonical URL.
- **Four ways to emit it** — the `Canonical` facade, the `canonical()` helper, the `<x-canonical>` component, and the `@canonical` directive all produce the same output.
- **Validated configuration** — the domain is validated on first use; a missing or malformed value throws `CanonicalConfigurationException` instead of emitting a broken tag.
- **Deferred and Octane-safe** — the service provider is deferred and binds everything as singletons, so nothing is resolved until you first ask for a URL.

## Requirements

- PHP **8.4+**
- Laravel **12.x** or **13.x**

See the [version compatibility](/laravel-canonical/configuration#version-compatibility)
table for the PHP and Laravel range of every release line.

## Installation

```bash
composer require fkrzski/laravel-canonical
```

The service provider and `Canonical` facade are auto-discovered. Publish the
config to override the defaults:

```bash
php artisan vendor:publish --tag="canonical-config"
```

Set your canonical domain in `.env` — it falls back to `APP_URL` when unset:

```dotenv
CANONICAL_DOMAIN=https://example.com
```

## Quickstart

```blade
<head>
    {{-- Current request URL, normalised against the canonical domain --}}
    <x-canonical />

    {{-- Or a specific path --}}
    <x-canonical path="/products/item" />
</head>
```

Both render the same tag:

```html
<link rel="canonical" href="https://example.com/products/item" />
```

Prefer PHP? The facade returns the string directly:

```php
use Fkrzski\LaravelCanonical\Facades\Canonical;

Canonical::generate('/products/item'); // https://example.com/products/item
Canonical::generate();                 // current request URI, normalised
```

## Next steps

- [Guide](/laravel-canonical/guide) — generating URLs, normalisation rules, query parameters, and the configuration exception.
- [Configuration](/laravel-canonical/configuration) — the config file, the canonical domain, trailing-slash handling, and domain validation.
- [Blade integration](/laravel-canonical/blade) — the `<x-canonical>` component, the `@canonical` directive, and the `canonical()` helper in templates.
- [API reference](/laravel-canonical/api-reference) — every entry point, its signature, return type, and the errors it can raise.
- [Testing](/laravel-canonical/testing) — assert on generated URLs and rendered tags with Pest and Testbench.
