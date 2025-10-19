<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Services;

final readonly class CanonicalUrlBuilder
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
