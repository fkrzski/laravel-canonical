---
title: Blade integration
description: Emit the canonical link tag in Blade — the x-canonical component, the @canonical directive, and the canonical() helper, and when to reach for each.
---

The package ships three ways to emit a `<link rel="canonical">` tag from a Blade
template. All three go through the same generator, so they produce identical
output — pick whichever reads best in your layout.

## The component

`<x-canonical>` renders the full link tag. With no attribute it canonicalises the
current request; pass a `path` to override it:

```blade
<head>
    {{-- Current request URL --}}
    <x-canonical />

    {{-- A specific path --}}
    <x-canonical path="/products/item" />

    {{-- A dynamic path --}}
    <x-canonical :path="$post->canonical_path" />
</head>
```

Each renders a single, HTML-escaped tag:

```html
<link rel="canonical" href="https://example.com/products/item" />
```

An empty string or `/` for `path` falls back to the bare canonical domain, so
`<x-canonical path="" />` and `<x-canonical path="/" />` both emit
`https://example.com`.

## The directive

`@canonical` emits the same tag with less markup. It takes an optional path
expression — a string literal or a variable:

```blade
<head>
    {{-- Current request URL --}}
    @canonical

    {{-- A string literal --}}
    @canonical('/products/item')

    {{-- A variable --}}
    @canonical($canonicalPath)
</head>
```

The directive escapes the URL with `e()` before printing, so query strings and
other entities are safe in an attribute.

## The helper in templates

The `canonical()` helper is a hybrid: called **with** a path it returns the URL
string; called **without** arguments it returns the generator, so you can chain
`->generate()`. Use it when you want the raw URL rather than a full tag — for an
Open Graph tag, a header, or a redirect:

```blade
<head>
    {{-- Direct URL --}}
    <link rel="canonical" href="{{ canonical('/products/item') }}" />

    {{-- Fluent form --}}
    <link rel="canonical" href="{{ canonical()->generate('/products/item') }}" />

    {{-- Current request URL --}}
    <link rel="canonical" href="{{ canonical()->generate() }}" />

    {{-- Reuse the same URL elsewhere --}}
    <meta property="og:url" content="{{ canonical() }}" />
</head>
```

It works in plain PHP too:

```php
$url       = canonical('/products/item'); // "https://example.com/products/item"
$generator = canonical();                 // CanonicalUrlGeneratorInterface instance
```

## Which one to use

- **`<x-canonical>`** — the clearest default; renders the complete tag and reads well in a `<head>`.
- **`@canonical`** — the same tag with the least markup, when you don't need attributes.
- **`canonical()`** — when you want the URL *string* (not a `<link>` tag) to reuse elsewhere.

## Publishing the view

The component's markup lives in a package view. Publish it to customise the tag —
to add attributes, for example:

```bash
php artisan vendor:publish --tag="canonical-views"
```

This copies the Blade view to `resources/views/vendor/canonical`, where your
version takes precedence over the packaged one.
