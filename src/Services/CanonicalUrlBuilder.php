<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Services;

use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface;

final readonly class CanonicalUrlBuilder implements CanonicalUrlBuilderInterface
{
    public function build(string $baseUrl, string $path): string
    {
        $baseUrl = rtrim($baseUrl, '/');
        $path = trim($path, '/');

        if ($path === '') {
            return $baseUrl;
        }

        return $baseUrl.'/'.$path;
    }
}
