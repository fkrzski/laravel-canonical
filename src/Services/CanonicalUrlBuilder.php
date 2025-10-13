<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Services;

final readonly class CanonicalUrlBuilder
{
    public function build(string $baseUrl, string $path): string
    {
        return trim(trim($baseUrl, '/').'/'.trim($path, '/'), '/');
    }
}
