<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Runtime;

final class ComponentViewResolver
{
    /** @var array<string, string> */
    private array $views = [
        'layout' => 'studio::runtime.layout',
        'resource' => 'studio::runtime.resource',
        'form' => 'studio::runtime.form',
        'input' => 'studio::runtime.input',
    ];

    public function resolve(string $component): string
    {
        return $this->views[$component] ?? 'studio::runtime.generic';
    }

    public function register(string $component, string $view): self
    {
        $this->views[$component] = $view;

        return $this;
    }
}
