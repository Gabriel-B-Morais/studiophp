<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Compiler;

final readonly class SourceFile
{
  public function __construct(
    public string $absolutePath,
    public string $relativePath,
    public string $contents,
    public string $hash,
  ) {}
}
