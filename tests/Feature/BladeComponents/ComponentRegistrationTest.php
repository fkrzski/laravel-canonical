<?php

declare(strict_types=1);

use Fkrzski\LaravelCanonical\CanonicalServiceProvider;
use Fkrzski\LaravelCanonical\View\Components\Canonical;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

mutates(CanonicalServiceProvider::class);

describe('Blade Components - Component Registration', function (): void {
    it('registers canonical blade component', function (): void {
        $component = Blade::getClassComponentAliases();

        expect($component)->toHaveKey('canonical');
    });

    it('resolves canonical component from container', function (): void {
        $component = $this->app->make(Canonical::class);

        expect($component)->toBeInstanceOf(Canonical::class);
    });

    it('registers canonical blade directive', function (): void {
        $directives = Blade::getCustomDirectives();

        expect($directives)->toHaveKey('canonical');
    });

    it('canonical directive is callable', function (): void {
        $directives = Blade::getCustomDirectives();

        expect($directives['canonical'])->toBeCallable();
    });

    it('helper function is available after service provider boot', function (): void {
        new CanonicalServiceProvider($this->app)->boot();

        expect(function_exists('canonical'))->toBeTrue();
    });

    it('component view file exists', function (): void {
        $viewPath = __DIR__.'/../../../resources/views/components/canonical.blade.php';

        expect(file_exists($viewPath))->toBeTrue();
    });

    it('helper file exists', function (): void {
        $helperPath = __DIR__.'/../../../src/helpers.php';

        expect(file_exists($helperPath))->toBeTrue();
    });

    it('publishes views with canonical-views tag', function (): void {
        ServiceProvider::$publishGroups = [];

        new CanonicalServiceProvider($this->app)->boot();

        $publishGroups = ServiceProvider::$publishGroups;

        expect($publishGroups)->toHaveKey('canonical-views');

        $viewsPublishPath = $publishGroups['canonical-views'];

        expect($viewsPublishPath)->toBeArray()
            ->and($viewsPublishPath)->not->toBeEmpty();

        $sourcePath = array_key_first($viewsPublishPath);

        expect($sourcePath)->toContain('resources/views')
            ->and($sourcePath)->toContain('src')
            ->and($sourcePath)->toEndWith('resources/views')
            // Verify the path is absolute and correctly formed
            ->and(is_dir($sourcePath))->toBeTrue();
    });
});
