<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Validation\BaseUrlValidator;

mutates(BaseUrlValidator::class);

describe('BaseUrlValidator - Unit Tests', function (): void {
    it('is instantiable', function (): void {
        $validator = new BaseUrlValidator;

        expect($validator)->toBeInstanceOf(BaseUrlValidator::class);
    });

    it('has validate method', function (): void {
        $validator = new BaseUrlValidator;

        expect(method_exists($validator, 'validate'))->toBeTrue();
    });
});
