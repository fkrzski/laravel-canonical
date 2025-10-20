<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Contracts;

interface CanonicalUrlGeneratorInterface
{
    public function generate(?string $path = null): string;
}
