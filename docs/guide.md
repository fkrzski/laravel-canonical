---
title: Guide
description: Generate canonical URLs with the Canonical facade — from the current request or an explicit path, how normalisation works, and the configuration exception.
---

Once the package is [installed and configured](/laravel-canonical/configuration),
generating a canonical URL is a single call. Everything routes through one
generator: the `Canonical` facade, the `canonical()` helper, the Blade component,
and the `@canonical` directive all build the same string the same way.

## Generating a URL

The `Canonical` facade is the primary entry point. Call `generate()` with no
argument to canonicalise the **current request**, or pass an explicit path to
override it:

```php
use Fkrzski\LaravelCanonical\Facades\Canonical;

// Current request URI, normalised against the canonical domain.
// Request: /blog/post  →  https://example.com/blog/post
Canonical::generate();

// Explicit path — independent of the current request.
Canonical::generate('/products/item'); // https://example.com/products/item
```

The path is joined onto the configured [canonical domain](/laravel-canonical/configuration#the-canonical-domain),
never the host the request actually arrived on — so a request to a staging or
`www` host still emits the canonical apex URL.

## How a path is normalised

Whatever you pass, the generated URL is built deterministically:

- A **leading slash is optional** — `generate('blog')` and `generate('/blog')` are equivalent.
- A **trailing slash is trimmed** by default — `/blog/` becomes `/blog`. This is configurable; see [trailing-slash handling](/laravel-canonical/configuration#trailing-slash-handling).
- The **root path collapses to the bare domain** — `generate('/')` returns `https://example.com`, with no trailing slash.
- **Query parameters are preserved** — they are part of the path and pass through untouched.

```php
Canonical::generate('/blog/');            // https://example.com/blog
Canonical::generate('/');                 // https://example.com
Canonical::generate('/blog/post/123');    // https://example.com/blog/post/123
```

## Query parameters

Because `generate()` reads the request URI (not just the path), query strings on
the current request are carried into the canonical URL:

```php
// Request: /search?q=laravel&page=2
Canonical::generate(); // https://example.com/search?q=laravel&page=2
```

Pass a path with its own query string to override the request entirely:

```php
Canonical::generate('/search?q=laravel'); // https://example.com/search?q=laravel
```

If your canonical strategy is to *drop* tracking parameters, generate from a clean
explicit path rather than the current request.

## The configuration exception

The canonical domain is validated the first time a URL is generated. An unset or
malformed domain throws `CanonicalConfigurationException` rather than emitting a
broken tag:

```php
use Fkrzski\LaravelCanonical\Exceptions\CanonicalConfigurationException;

try {
    Canonical::generate('/page');
} catch (CanonicalConfigurationException $e) {
    // Domain missing, or not a valid http/https URL.
}
```

Which values are accepted — and which raise this exception — is covered in
[domain validation](/laravel-canonical/configuration#domain-validation).

## Where to go next

- Emitting the tag in Blade — the [component, directive, and helper](/laravel-canonical/blade).
- Every signature and return type — the [API reference](/laravel-canonical/api-reference).
