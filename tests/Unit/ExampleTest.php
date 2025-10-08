<?php

declare(strict_types=1);

use VendorName\Skeleton\SkeletonClass;

mutates(SkeletonClass::class);

it('foo', function (): void {
    $example = new SkeletonClass;

    expect($example)->toBeObject();
});
