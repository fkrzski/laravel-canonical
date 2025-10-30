<?php

declare(strict_types=1);

namespace Fkrzski\LaravelCanonical\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Canonical extends Component
{
    public function __construct(
        public ?string $path = null
    ) {}

    public function render(): View
    {
        /** @var view-string $viewName */
        $viewName = 'canonical::components.canonical';

        return view($viewName);
    }

    public function canonicalUrl(): string
    {
        return canonical()->generate($this->path);
    }
}
