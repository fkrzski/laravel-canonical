<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Tests;

use Fkrzski\LaravelCanonical\CanonicalServiceProvider;
use Fkrzski\LaravelCanonical\Facades\Canonical;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CanonicalServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Canonical' => Canonical::class,
        ];
    }
}
