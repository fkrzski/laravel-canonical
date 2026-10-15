# Changelog

All notable changes to `laravel-canonical` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.4.3] - 2026-10-15

### Changed

- This file follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) 1.1.0: every release from 1.0.0 up says what it added, changed or fixed for someone using the package, where it listed pull request titles with bare URLs and left 1.1.0 and 1.0.0 out altogether ([#15](https://github.com/fkrzski/laravel-canonical/issues/15)).
- An entry lands under `[Unreleased]` in the pull request that makes the change, and the release moves it under its version. `stefanzweifel/changelog-updater-action` used to write the file from each release body after the fact, so nothing described the change itself ([#15](https://github.com/fkrzski/laravel-canonical/issues/15)).

### Removed

- `CHANGELOG.md` from the Composer dist archive (`export-ignore`), as with the README and the test suite ([#15](https://github.com/fkrzski/laravel-canonical/issues/15)).

## [1.4.2] - 2026-10-14

### Changed

- The documentation pages are MDX and carry the docs hub's Starlight components — the install and usage variants in `<Tabs>`, the caveats as `<Aside>`, the setup as `<Steps>` and the published paths as `<FileTree>` — where they were flat Markdown ([#13](https://github.com/fkrzski/laravel-canonical/pull/13), [#14](https://github.com/fkrzski/laravel-canonical/pull/14)).

### Fixed

- The Open Graph example on the Blade page echoes `canonical()->generate()`, where it echoed a bare `canonical()`: without arguments the helper hands back the generator, so the copied snippet threw instead of printing a URL ([#13](https://github.com/fkrzski/laravel-canonical/pull/13)).
- The configuration and API pages put the domain validation where it happens — the first time the generator is resolved from the container, a bare `canonical()` included — instead of promising it on the first generated URL ([#13](https://github.com/fkrzski/laravel-canonical/pull/13)).

## [1.4.1] - 2026-07-26

### Added

- Documentation at [docs.fkrzski.dev/laravel-canonical](https://docs.fkrzski.dev/laravel-canonical): a guide, the configuration reference, the Blade entry points, an API reference and a page on testing, validated in CI against the shared `@fkrzski/docs-schema` contract ([#12](https://github.com/fkrzski/laravel-canonical/pull/12)).

### Fixed

- Laravel's package discovery registers `CanonicalServiceProvider` and the `Canonical` facade alias. `extra.laravel` in `composer.json` spelled both class names with doubled backslashes, which resolve to no class at all, so an application that did not register them by hand got neither ([#12](https://github.com/fkrzski/laravel-canonical/pull/12)).

## [1.4.0] - 2026-03-19

### Changed

- Requires `illuminate/support` `^12.0 | ^13.0`, so the package installs on Laravel 12 and 13 and no longer on Laravel 11 ([#9](https://github.com/fkrzski/laravel-canonical/pull/9)).

## [1.3.0] - 2025-12-11

### Changed

- Requires PHP `^8.4 | ^8.5`, where 1.2.0 still installed on 8.3 ([#6](https://github.com/fkrzski/laravel-canonical/pull/6)).

## [1.2.0] - 2025-10-30

### Added

- `<x-canonical />`, a Blade component rendering `<link rel="canonical" href="..." />` for the current request, or for the path given as its `path` prop. Its view publishes with `php artisan vendor:publish --tag="canonical-views"` ([#4](https://github.com/fkrzski/laravel-canonical/pull/4)).
- `@canonical`, a Blade directive printing the same tag, with the path as an optional expression ([#4](https://github.com/fkrzski/laravel-canonical/pull/4)).
- `canonical()`, a helper autoloaded with the package: given a path it returns the canonical URL as a string, and without arguments the `CanonicalUrlGeneratorInterface` itself, to chain `generate()` on ([#4](https://github.com/fkrzski/laravel-canonical/pull/4)).

## [1.1.0] - 2025-10-29

### Added

- `canonical.trim_trailing_slash` (`CANONICAL_TRIM_TRAILING_SLASH`), `true` by default and trimming as 1.0.0 did. Set to `false`, a path that came in with a trailing slash keeps it, the bare domain included ([#2](https://github.com/fkrzski/laravel-canonical/pull/2)).

### Changed

- `CanonicalConfigInterface` declares `shouldTrimTrailingSlash()`, and `CanonicalUrlBuilder` takes a `CanonicalConfigInterface`, so an own implementation of the contract, or a builder constructed by hand, has to supply them ([#2](https://github.com/fkrzski/laravel-canonical/pull/2)).

## [1.0.0] - 2025-10-20

### Added

- `Canonical` facade over `CanonicalUrlGeneratorInterface::generate()`: without an argument it returns the canonical URL of the current request, its query string included, and with a path the canonical URL of that path. Trailing slashes are removed.
- `canonical.domain` (`CANONICAL_DOMAIN`, falling back to `APP_URL` and then to `http://localhost`), the base every generated URL is built on, published with `php artisan vendor:publish --tag="canonical-config"`.
- `CanonicalConfigurationException`, thrown when the configured domain is empty, is not a valid URL, uses a scheme other than `http` or `https`, or carries no host.
- `CanonicalServiceProvider`, a deferred provider binding `BaseUrlValidatorInterface`, `CanonicalConfigInterface`, `CanonicalUrlBuilderInterface` and `CanonicalUrlGeneratorInterface` as singletons, each of them replaceable.

[Unreleased]: https://github.com/fkrzski/laravel-canonical/compare/1.4.3...HEAD
[1.4.3]: https://github.com/fkrzski/laravel-canonical/compare/1.4.2...1.4.3
[1.4.2]: https://github.com/fkrzski/laravel-canonical/compare/1.4.1...1.4.2
[1.4.1]: https://github.com/fkrzski/laravel-canonical/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/fkrzski/laravel-canonical/compare/1.3.0...1.4.0
[1.3.0]: https://github.com/fkrzski/laravel-canonical/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/fkrzski/laravel-canonical/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/fkrzski/laravel-canonical/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/fkrzski/laravel-canonical/releases/tag/1.0.0
