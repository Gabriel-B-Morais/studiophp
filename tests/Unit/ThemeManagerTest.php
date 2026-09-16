<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Tests\Unit;

use GabrielBMorais\Studio\UI\Theme\ThemeManager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ThemeManagerTest extends TestCase
{
    #[Test]
    public function explicit_supported_themes_are_preserved(): void
    {
        $manager = new ThemeManager();

        self::assertSame('light', $manager->resolve('light'));
        self::assertSame('dark', $manager->resolve('dark'));
        self::assertSame('system', $manager->resolve('system'));
    }

    #[Test]
    public function unsupported_theme_falls_back_to_light(): void
    {
        $manager = new ThemeManager();

        self::assertSame('light', $manager->resolve('neon'));
    }
}
