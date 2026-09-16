<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Runtime;

use GabrielBMorais\Studio\Compiler\SourceFile;

final readonly class StudioRouteMapper
{
    public function __construct(
        private string $routePrefix = '',
        private string $routeNamePrefix = 'studio.',
    ) {
    }

    public function uriFor(SourceFile $source): string
    {
        $path = $this->withoutBladeExtension($source->relativePath);
        $segments = array_values(array_filter(explode('/', trim($path, '/')), static fn (string $segment): bool => $segment !== ''));

        if (($segments[array_key_last($segments)] ?? null) === 'index') {
            array_pop($segments);
        }

        $uri = implode('/', $segments);
        $prefix = trim($this->routePrefix, '/');

        if ($prefix !== '') {
            $uri = trim($prefix.'/'.ltrim($uri, '/'), '/');
        }

        return $uri === '' ? '/' : '/'.$uri;
    }

    public function nameFor(SourceFile $source): string
    {
        $path = $this->withoutBladeExtension($source->relativePath);
        $segments = array_values(array_filter(explode('/', trim($path, '/')), static fn (string $segment): bool => $segment !== ''));

        if ($segments === []) {
            $segments = ['index'];
        }

        return $this->routeNamePrefix.implode('.', $segments);
    }

    public function viewFor(SourceFile $source): string
    {
        return str_replace('/', '.', $this->withoutBladeExtension($source->relativePath));
    }

    private function withoutBladeExtension(string $path): string
    {
        return str_ends_with($path, '.blade.php')
            ? substr($path, 0, -strlen('.blade.php'))
            : $path;
    }
}
