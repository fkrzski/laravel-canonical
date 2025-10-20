<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Contracts;

interface CanonicalConfigInterface
{
    public function getBaseUrl(): string;
}
