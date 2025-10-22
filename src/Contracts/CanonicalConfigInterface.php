<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Contracts;

interface CanonicalConfigInterface
{
    public function getBaseUrl(): string;

    /**
     * @since 1.1.0
     */
    public function shouldTrimTrailingSlash(): bool;
}
