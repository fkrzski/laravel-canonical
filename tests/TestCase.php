<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Tests;

use Fkrzski\LaravelCanonical\LaravelCanonicalServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

final class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelCanonicalServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'LaravelCanonical' => \Fkrzski\LaravelCanonical\Facades\LaravelCanonical::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup environment if needed
        // config()->set('database.default', 'testing');
    }
}
