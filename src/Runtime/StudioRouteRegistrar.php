<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Runtime;

use GabrielBMorais\Studio\Compiler\SourceScanner;
use GabrielBMorais\Studio\Http\StudioPageController;
use Illuminate\Routing\Router;
use RuntimeException;

final readonly class StudioRouteRegistrar
{
    public function __construct(
        private Router $router,
        private SourceScanner $scanner,
        private StudioRouteMapper $mapper,
    ) {
    }

    public function register(): int
    {
        $sources = $this->scanner->scan();
        $studioUris = [];

        foreach ($sources as $source) {
            $uri = $this->mapper->uriFor($source);

            if (isset($studioUris[$uri])) {
                throw new RuntimeException(sprintf(
                    'Studio source routes [%s] and [%s] both resolve to [%s].',
                    $studioUris[$uri],
                    $source->relativePath,
                    $uri,
                ));
            }

            $studioUris[$uri] = $source->relativePath;
        }

        $registered = 0;

        foreach ($sources as $source) {
            $uri = $this->mapper->uriFor($source);

            // Application routes are authoritative. Studio only fills routes that do not exist.
            if ($this->hasGetRoute($uri)) {
                continue;
            }

            $route = $this->router->get($uri, StudioPageController::class);
            $route->middleware('web');
            $route->name($this->mapper->nameFor($source));
            $route->setAction(array_merge($route->getAction(), [
                'studio_view' => $this->mapper->viewFor($source),
            ]));

            $registered++;
        }

        return $registered;
    }

    private function hasGetRoute(string $uri): bool
    {
        $normalized = trim($uri, '/');

        foreach ($this->router->getRoutes()->getRoutes() as $route) {
            if (! in_array('GET', $route->methods(), true)) {
                continue;
            }

            if (trim($route->uri(), '/') === $normalized) {
                return true;
            }
        }

        return false;
    }
}
