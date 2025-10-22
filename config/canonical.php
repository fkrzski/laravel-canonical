<?php

declare(strict_types=1);

/**
 * Configuration file for Laravel Canonical
 *
 * You can publish this file with:
 * php artisan vendor:publish --tag="canonical-config"
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Canonical Domain
    |--------------------------------------------------------------------------
    |
    | This value is the canonical domain that will be used for generating
    | canonical URLs. If not specified, it will fall back to APP_URL
    | environment variable.
    |
    */
    'domain' => env('CANONICAL_DOMAIN', env('APP_URL', 'http://localhost')),

    /*
    |--------------------------------------------------------------------------
    | Trim Trailing Slash
    |--------------------------------------------------------------------------
    |
    | Determines whether trailing slashes should be removed from generated
    | canonical URLs. When set to true, URLs like /blog/ will be normalized
    | to /blog. When set to false, the original URL format is preserved.
    |
    | @since 1.1.0
    |
    */
    'trim_trailing_slash' => env('CANONICAL_TRIM_TRAILING_SLASH', true),
];
