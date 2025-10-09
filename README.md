# Laravel package for managing canonical URLs and preventing duplicate content

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fkrzski/laravel-canonical.svg?style=for-the-badge)](https://packagist.org/packages/fkrzski/laravel-canonical)
[![Tests](https://img.shields.io/github/actions/workflow/status/fkrzski/laravel-canonical/run-tests.yml?branch=main&label=tests&style=for-the-badge)](https://github.com/fkrzski/laravel-canonical/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/fkrzski/laravel-canonical.svg?style=for-the-badge)](https://packagist.org/packages/fkrzski/laravel-canonical)

This is where your description should go. Try and limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require fkrzski/laravel-canonical
```

## Publishing Config

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-canonical-config"
```

## Usage

```php
$class = new Fkrzski\LaravelCanonical\LaravelCanonicalClass();
echo $class->echoPhrase('Hello, Fkrzski!');
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
