<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Tests;

use Fkrzski\LaravelCanonical\CanonicalServiceProvider;
use Fkrzski\LaravelCanonical\Facades\Canonical;
use Illuminate\Support\Facades\Blade;
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

    /**
     * Render a Blade template with the given data.
     *
     * @param  array<string, mixed>  $data
     */
    protected function blade(string $template, $data = []): string
    {
        $rendered = Blade::render($template, $data);

        return trim($rendered);
    }
}
