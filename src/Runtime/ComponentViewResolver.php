<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Runtime;

final class ComponentViewResolver
{
    /** @var array<string, string> */
    private array $views = [
        'layout' => 'studio::runtime.layout',
        'container' => 'studio::runtime.container',
        'stack' => 'studio::runtime.stack',
        'grid' => 'studio::runtime.grid',
        'card' => 'studio::runtime.card',
        'resource' => 'studio::runtime.resource',
        'form' => 'studio::runtime.form',
        'field' => 'studio::runtime.field',
        'label' => 'studio::runtime.label',
        'description' => 'studio::runtime.description',
        'error' => 'studio::runtime.error',
        'input' => 'studio::runtime.input',
        'textarea' => 'studio::runtime.textarea',
        'select' => 'studio::runtime.select',
        'checkbox' => 'studio::runtime.checkbox',
        'switch' => 'studio::runtime.switch',
        'button' => 'studio::runtime.button',
        'alert' => 'studio::runtime.alert',
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
