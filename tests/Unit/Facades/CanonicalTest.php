<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\Facades\Canonical;
use Fkrzski\LaravelCanonical\LaravelCanonicalClass;
use Illuminate\Support\Facades\Facade;

mutates(Canonical::class);

describe('Canonical Facade', function (): void {
    it('extends Facade class', function (): void {
        expect(new Canonical)->toBeInstanceOf(Facade::class);
    });

    it('returns correct facade accessor', function (): void {
        expect(Canonical::getFacadeAccessor())->toBe(LaravelCanonicalClass::class);
    });

    it('resolves to LaravelCanonicalClass instance', function (): void {
        expect(Canonical::getFacadeRoot())->toBeInstanceOf(LaravelCanonicalClass::class);
    });
});
