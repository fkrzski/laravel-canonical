<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Facades;

use Fkrzski\LaravelCanonical\LaravelCanonicalClass;
use Illuminate\Support\Facades\Facade;

/**
 * @see LaravelCanonicalClass
 */
final class Canonical extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return LaravelCanonicalClass::class;
    }
}
