<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Exceptions;

use RuntimeException;

final class StudioCompileException extends RuntimeException
{
  public function __construct(
    string $message,
    public readonly string $sourceFile,
    public readonly int $sourceLine,
    public readonly ?string $component = null,
  ) {
    $prefix = sprintf('%s:%d', $sourceFile, $sourceLine);

    if ($component !== null) {
      $prefix .= sprintf(' — <studio:%s>', $component);
    }

    parent::__construct($prefix . ' ' . $message);
  }
}
