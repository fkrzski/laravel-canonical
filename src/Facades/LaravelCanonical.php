<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Fkrzski\LaravelCanonical\LaravelCanonicalClass
 */
final class LaravelCanonical extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Fkrzski\LaravelCanonical\LaravelCanonicalClass::class;
    }
}
