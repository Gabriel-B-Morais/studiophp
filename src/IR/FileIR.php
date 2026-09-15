<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\IR;

final readonly class FileIR
{
  /** @param list<ComponentIR> $components */
  public function __construct(
    public string $path,
    public string $hash,
    public array $components,
  ) {}

  /** @return array<string, mixed> */
  public function toArray(): array
  {
    return [
      'path' => $this->path,
      'hash' => $this->hash,
      'components' => array_map(static fn(ComponentIR $component): array => $component->toArray(), $this->components),
    ];
  }
}
