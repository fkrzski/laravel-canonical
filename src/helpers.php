<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Contracts\CanonicalUrlGeneratorInterface;

if (! function_exists('canonical')) { // @codeCoverageIgnore
    /**
     * Get canonical URL generator instance or generate canonical URL.
     *
     * When called without arguments, returns the generator instance for fluent usage.
     * When called with a path, returns the canonical URL string.
     *
     * @param  string|null  $path  Optional path to generate canonical URL for
     * @return ($path is null ? CanonicalUrlGeneratorInterface : string)
     *
     * @example
     * // Get generator instance
     * canonical()->generate('/path');
     *
     * // Generate URL directly
     * canonical('/path'); // "https://example.com/path"
     */
    function canonical(?string $path = null): CanonicalUrlGeneratorInterface|string
    {
        $generator = app(CanonicalUrlGeneratorInterface::class);

        if ($path === null) {
            return $generator;
        }

        return $generator->generate($path);
    }
} // @codeCoverageIgnore
