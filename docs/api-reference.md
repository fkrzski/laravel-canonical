---
title: API reference
description: Every laravel-canonical entry point — the Canonical facade, the canonical() helper, the x-canonical component, and the @canonical directive, with signatures.
---

Every public entry point routes through `CanonicalUrlGeneratorInterface`, resolved
from the container. The facade, helper, component, and directive are thin wrappers
over its single `generate()` method.

## `Canonical` facade

`Fkrzski\LaravelCanonical\Facades\Canonical`.

### `generate()`

```text
Canonical::generate(?string $path = null): string
```

Builds the canonical URL for `$path`, joined onto the configured
[canonical domain](/laravel-canonical/configuration#the-canonical-domain). With no
argument it canonicalises the **current request URI** (query string included).

- Returns the normalised URL as a `string`.
- Throws `CanonicalConfigurationException` when the domain is unset or invalid.

```php
use Fkrzski\LaravelCanonical\Facades\Canonical;

Canonical::generate();                 // current request, normalised
Canonical::generate('/products/item'); // https://example.com/products/item
Canonical::generate('/');              // https://example.com
```

## `canonical()` helper

```text
canonical(?string $path = null): CanonicalUrlGeneratorInterface|string
```

A hybrid helper. Called **with** a path it returns the URL string; called
**without** arguments it returns the generator for fluent use. The return type is
conditional (`($path is null ? Generator : string)`), so static analysis narrows
it for you.

- `canonical('/path')` → `string`.
- `canonical()` → `CanonicalUrlGeneratorInterface`.

```php
$url       = canonical('/products/item'); // "https://example.com/products/item"
$generator = canonical();                 // fluent generator
$url       = canonical()->generate('/products/item');
```

## `<x-canonical>` component

`Fkrzski\LaravelCanonical\View\Components\Canonical`, registered as `x-canonical`.

```text
<x-canonical path="?string" />
```

Renders a complete, HTML-escaped `<link rel="canonical" href="…" />` tag. The
optional `path` attribute overrides the current request; `null`, `''`, and `'/'`
all fall back to the bare canonical domain.

- Renders a single-line `<link>` tag.
- Throws `CanonicalConfigurationException` when the domain is unset or invalid.

```blade
<x-canonical />
<x-canonical path="/products/item" />
<x-canonical :path="$post->canonical_path" />
```

## `@canonical` directive

```text
@canonical(?string $expression = null)
```

Echoes the same `<link rel="canonical">` tag as the component, escaping the URL
with `e()`. The optional expression is a path string or variable.

```blade
@canonical
@canonical('/products/item')
@canonical($canonicalPath)
```

## Exceptions

### `CanonicalConfigurationException`

`Fkrzski\LaravelCanonical\Exceptions\CanonicalConfigurationException` extends
`Exception`. Thrown the first time a URL is generated when the canonical domain is:

- unset or empty (`Canonical domain is not set in config.`),
- not a syntactically valid URL,
- not using the `http` or `https` scheme, or
- missing a host.

See [domain validation](/laravel-canonical/configuration#domain-validation) for
the accepted forms.

## Container bindings

The [deferred](https://laravel.com/docs/providers#deferred-providers) service
provider binds these contracts as singletons; resolve them directly for custom
wiring or testing:

| Contract | Bound to |
|----------|----------|
| `CanonicalUrlGeneratorInterface` | `CanonicalUrlGenerator` |
| `CanonicalConfigInterface` | `CanonicalConfig` |
| `CanonicalUrlBuilderInterface` | `CanonicalUrlBuilder` |
| `BaseUrlValidatorInterface` | `BaseUrlValidator` |
