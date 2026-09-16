<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Http;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use RuntimeException;

final readonly class StudioPageController
{
    public function __construct(private ViewFactory $views)
    {
    }

    public function __invoke(Request $request): View
    {
        $route = $request->route();
        $view = is_object($route) && method_exists($route, 'getAction')
            ? $route->getAction('studio_view')
            : null;

        if (! is_string($view) || $view === '') {
            throw new RuntimeException('Studio runtime route is missing its view action metadata.');
        }

        return $this->views->make('studio-app::'.$view);
    }
}
