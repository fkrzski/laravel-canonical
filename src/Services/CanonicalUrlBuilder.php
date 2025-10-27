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
        $baseUrlHadTrailingSlash = str_ends_with($baseUrl, '/');
        $pathHadTrailingSlash = str_ends_with($path, '/');

        $baseUrl = rtrim($baseUrl, '/');
        $path = trim($path, '/');

        if (! $this->config->shouldTrimTrailingSlash() && $pathHadTrailingSlash && $path !== '') {
            $path .= '/';
        }

        if ($path === '') {
            return $this->shouldAddTrailingSlashForEmptyPath($baseUrlHadTrailingSlash, $pathHadTrailingSlash)
                ? $baseUrl.'/'
                : $baseUrl;
        }

        return $baseUrl.'/'.$path;
    }

    private function shouldAddTrailingSlashForEmptyPath(bool $baseUrlHadTrailingSlash, bool $pathHadTrailingSlash): bool
    {
        return ($baseUrlHadTrailingSlash || $pathHadTrailingSlash) && ! $this->config->shouldTrimTrailingSlash();
    }
}
