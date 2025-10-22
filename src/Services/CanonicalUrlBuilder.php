<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Services;

use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface;

final readonly class CanonicalUrlBuilder implements CanonicalUrlBuilderInterface
{
    public function __construct(
        private CanonicalConfigInterface $config
    ) {}

    public function build(string $baseUrl, string $path): string
    {
        $baseUrl = rtrim($baseUrl, '/');

        if ($this->config->shouldTrimTrailingSlash()) {
            $path = trim($path, '/');
        } else {
            $path = ltrim($path, '/');
        }

        if ($path === '') {
            return $baseUrl;
        }

        return $baseUrl.'/'.$path;
    }
}
