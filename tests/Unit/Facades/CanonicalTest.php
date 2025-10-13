<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalUrlGenerator;
use Fkrzski\LaravelCanonical\Facades\Canonical;
use Illuminate\Support\Facades\Facade;

mutates(Canonical::class);

describe('Canonical Facade', function (): void {
    it('extends Facade class', function (): void {
        expect(new Canonical)->toBeInstanceOf(Facade::class);
    });

    it('returns correct facade accessor', function (): void {
        expect(Canonical::getFacadeAccessor())->toBe(CanonicalUrlGenerator::class);
    });

    it('resolves to CanonicalUrlGenerator instance', function (): void {
        expect(Canonical::getFacadeRoot())->toBeInstanceOf(CanonicalUrlGenerator::class);
    });
});
