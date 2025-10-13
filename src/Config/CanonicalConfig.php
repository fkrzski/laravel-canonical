<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\Config;

use Fkrzski\LaravelCanonical\Exceptions\CanonicalConfigurationException;
use Fkrzski\LaravelCanonical\Validation\BaseUrlValidator;

final readonly class CanonicalConfig
{
    private string $baseUrl;

    /**
     * @throws CanonicalConfigurationException
     */
    public function __construct(
        BaseUrlValidator $validator
    ) {
        $domain = config()->string('canonical.domain');

        if (empty($domain)) {
            throw new CanonicalConfigurationException('Canonical domain is not set in config.');
        }

        $trimmedDomain = trim($domain, '/');

        $validator->validate($trimmedDomain);

        $this->baseUrl = $trimmedDomain;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
