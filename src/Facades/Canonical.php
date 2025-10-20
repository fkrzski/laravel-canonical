<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Facades;

use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlGeneratorInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @see CanonicalUrlGeneratorInterface
 */
final class Canonical extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return CanonicalUrlGeneratorInterface::class;
    }
}
