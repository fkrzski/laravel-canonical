<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical;

use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlBuilderInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlGeneratorInterface;

final readonly class CanonicalUrlGenerator implements CanonicalUrlGeneratorInterface
{
    public function __construct(
        private CanonicalConfigInterface $config,
        private CanonicalUrlBuilderInterface $builder
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
