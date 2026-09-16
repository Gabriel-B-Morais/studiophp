<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Tests\Unit;

use GabrielBMorais\Studio\Registry\ComponentRegistry;
use GabrielBMorais\Studio\Runtime\ComponentViewResolver;
use GabrielBMorais\Studio\Support\BuiltInComponents;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StudioUiRegistryTest extends TestCase
{
    #[Test]
    public function ui_components_are_registered_in_the_dsl(): void
    {
        $registry = new ComponentRegistry();
        BuiltInComponents::register($registry);

        foreach (['container', 'stack', 'grid', 'card', 'field', 'label', 'description', 'error', 'textarea', 'select', 'checkbox', 'switch', 'button', 'alert'] as $component) {
            self::assertNotNull($registry->resolve($component), sprintf('Expected [%s] to be registered.', $component));
        }
    }

    #[Test]
    public function ui_components_have_dedicated_runtime_views(): void
    {
        $resolver = new ComponentViewResolver();

        self::assertSame('studio::runtime.card', $resolver->resolve('card'));
        self::assertSame('studio::runtime.input', $resolver->resolve('input'));
        self::assertSame('studio::runtime.button', $resolver->resolve('button'));
        self::assertSame('studio::runtime.alert', $resolver->resolve('alert'));
        self::assertSame('studio::runtime.generic', $resolver->resolve('unknown-component'));
    }
}
