<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Facades;

use Fkrzski\LaravelCanonical\CanonicalUrlGenerator;
use Illuminate\Support\Facades\Facade;

/**
 * @see CanonicalUrlGenerator
 */
final class Canonical extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return CanonicalUrlGenerator::class;
    }
}
