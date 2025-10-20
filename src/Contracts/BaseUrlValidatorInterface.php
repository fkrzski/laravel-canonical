<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Contracts;

interface BaseUrlValidatorInterface
{
    public function validate(string $url): void;
}
