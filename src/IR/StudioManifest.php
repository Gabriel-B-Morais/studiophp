<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\IR;

final readonly class StudioManifest
{
  /** @param list<FileIR> $files */
  public function __construct(
    public int $irVersion,
    public array $files,
  ) {}

  /** @return array<string, mixed> */
  public function toArray(): array
  {
    return [
      'studio_ir_version' => $this->irVersion,
      'files' => array_map(static fn(FileIR $file): array => $file->toArray(), $this->files),
    ];
  }
}
