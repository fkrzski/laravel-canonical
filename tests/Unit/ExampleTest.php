<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\LaravelCanonicalClass;

mutates(LaravelCanonicalClass::class);

it('foo', function (): void {
    $example = new LaravelCanonicalClass;

    expect($example)->toBeObject();
});
