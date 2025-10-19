<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical;

use Fkrzski\LaravelCanonical\Config\CanonicalConfig;
use Fkrzski\LaravelCanonical\Services\CanonicalUrlBuilder;

final readonly class CanonicalUrlGenerator
{
    public function __construct(
        private CanonicalConfig $config,
        private CanonicalUrlBuilder $builder
    ) {}

    public function generate(?string $path = null): string
    {
        $uri = $path ?? $this->resolveCurrentPath();
        $baseUrl = $this->config->getBaseUrl();

        return $this->builder->build($baseUrl, $uri);
    }

    private function resolveCurrentPath(): string
    {
        return request()->getRequestUri();
    }
}
