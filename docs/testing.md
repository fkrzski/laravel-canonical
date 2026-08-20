---
title: Testing
description: Test canonical URLs with Pest and Testbench — set the domain in config, assert on generated strings, and assert on the rendered Blade component and directive.
---

The package has nothing to fake — it makes no HTTP calls. Testing is a matter of
setting the canonical domain in config and asserting on the string or tag that
comes out. The examples below use [Pest](https://pestphp.com) and
[Orchestra Testbench](https://github.com/orchestral/testbench), the package's own
test stack.

## Setting the domain

Every test starts by pointing `canonical.domain` at a known host, so assertions
are deterministic:

```php
use Fkrzski\LaravelCanonical\Facades\Canonical;

beforeEach(function (): void {
    config(['canonical.domain' => 'https://example.com']);
});
```

## Asserting on generated URLs

Call the facade (or the `canonical()` helper) and assert on the returned string:

```php
it('generates a canonical URL for a path', function (): void {
    expect(Canonical::generate('/products/item'))
        ->toBe('https://example.com/products/item');
});

it('normalises the root path to the bare domain', function (): void {
    expect(Canonical::generate('/'))->toBe('https://example.com');
});
```

## Asserting on the current request

`generate()` with no argument reads the current request, so bind a request into
the container to exercise that path:

```php
use Illuminate\Http\Request;

it('canonicalises the current request', function (): void {
    $this->app->instance('request', Request::create('https://staging.example.com/blog/post'));

    // Built from the canonical domain, not the request host.
    expect(Canonical::generate())->toBe('https://example.com/blog/post');
});
```

## Asserting on rendered Blade

Render the component or directive and assert on the emitted tag. Testbench's
`Blade::render()` (wrapped here as a `blade()` helper) is enough:

```php
use Illuminate\Support\Facades\Blade;

it('renders the canonical component', function (): void {
    $html = trim(Blade::render('<x-canonical path="/products/item" />'));

    expect($html)->toBe('<link rel="canonical" href="https://example.com/products/item" />');
});

it('renders the directive', function (): void {
    $html = trim(Blade::render("@canonical('/products/item')"));

    expect($html)->toBe('<link rel="canonical" href="https://example.com/products/item" />');
});
```

## Asserting on the configuration exception

The domain is only validated on first use, so the assertion has to trigger one.
An unset or malformed domain throws `CanonicalConfigurationException`:

```php
use Fkrzski\LaravelCanonical\Exceptions\CanonicalConfigurationException;

it('rejects a domain without a scheme', function (): void {
    config(['canonical.domain' => 'example.com']);

    expect(fn (): string => Canonical::generate('/page'))
        ->toThrow(CanonicalConfigurationException::class);
});
```

## Running the suite

The package's own tests run through Composer:

```bash
composer test
```
