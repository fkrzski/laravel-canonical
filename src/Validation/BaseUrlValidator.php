<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Validation;

use Fkrzski\LaravelCanonical\Contracts\BaseUrlValidatorInterface;
use Fkrzski\LaravelCanonical\Exceptions\CanonicalConfigurationException;

final readonly class BaseUrlValidator implements BaseUrlValidatorInterface
{
    /**
     * @throws CanonicalConfigurationException
     */
    public function validate(string $url): void
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new CanonicalConfigurationException(message: "Invalid URL format: '{$url}'. Expected a valid URL.");
        }

        $parsed = parse_url($url);

        if (! isset($parsed['scheme']) || ! in_array($parsed['scheme'], ['http', 'https'], true)) {
            throw new CanonicalConfigurationException(message: "Invalid URL scheme for '{$url}'. Only 'http' and 'https' schemes are allowed.");
        }

        if (empty($parsed['host'])) {
            throw new CanonicalConfigurationException(message: "Missing host in URL: '{$url}'.");
        }
    }
}
