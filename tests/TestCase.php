<?php

declare(strict_types=1);

namespace VendorName\Skeleton\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use VendorName\Skeleton\SkeletonServiceProvider;

final class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SkeletonServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Skeleton' => \VendorName\Skeleton\Facades\Skeleton::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup environment if needed
        // config()->set('database.default', 'testing');
    }
}
