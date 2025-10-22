<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Config;

use Fkrzski\LaravelCanonical\Contracts\BaseUrlValidatorInterface;
use Fkrzski\LaravelCanonical\Contracts\CanonicalConfigInterface;
use Fkrzski\LaravelCanonical\Exceptions\CanonicalConfigurationException;

final readonly class CanonicalConfig implements CanonicalConfigInterface
{
    private string $baseUrl;

    /**
     * @since 1.1.0
     */
    private bool $trimTrailingSlash;

    /**
     * @throws CanonicalConfigurationException
     */
    public function __construct(
        BaseUrlValidatorInterface $validator
    ) {
        $domain = config()->string('canonical.domain');

        if (empty($domain)) {
            throw new CanonicalConfigurationException('Canonical domain is not set in config.');
        }

        $trimmedDomain = trim($domain, '/');

        $validator->validate($trimmedDomain);

        $this->baseUrl = $trimmedDomain;
        $this->trimTrailingSlash = config()->boolean('canonical.trim_trailing_slash', true);
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @since 1.1.0
     */
    public function shouldTrimTrailingSlash(): bool
    {
        return $this->trimTrailingSlash;
    }
}
