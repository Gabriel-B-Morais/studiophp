<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\View\Components;

use GabrielBMorais\Studio\Runtime\ComponentViewResolver;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class RuntimeComponent extends Component
{
    public function __construct(public readonly string $studioComponent)
    {
    }

    public function render(): View
    {
        $resolver = app(ComponentViewResolver::class);

        return view($resolver->resolve($this->studioComponent), [
            'studioComponent' => $this->studioComponent,
        ]);
    }
}
