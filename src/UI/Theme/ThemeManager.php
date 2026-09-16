<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\UI\Theme;

final class ThemeManager
{
  private const THEMES = ['light', 'dark', 'system'];

  public function resolve(?string $requested = null): string
  {
    $theme = $requested ?: (string) config('studio.theme.default', 'light');

    return in_array($theme, self::THEMES, true) ? $theme : 'light';
  }
}
