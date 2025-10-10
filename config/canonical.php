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
];
