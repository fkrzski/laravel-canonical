<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Contracts;

interface CanonicalUrlBuilderInterface
{
    public function build(string $baseUrl, string $path): string;
}
