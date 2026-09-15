<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\IR;

final readonly class ComponentIR
{
  /**
   * @param list<AttributeIR> $attributes
   * @param list<ComponentIR> $children
   */
  public function __construct(
    public string $name,
    public int $line,
    public array $attributes,
    public array $children,
  ) {}

  /** @return array<string, mixed> */
  public function toArray(): array
  {
    return [
      'name' => $this->name,
      'line' => $this->line,
      'attributes' => array_map(static fn(AttributeIR $attribute): array => $attribute->toArray(), $this->attributes),
      'children' => array_map(static fn(ComponentIR $child): array => $child->toArray(), $this->children),
    ];
  }
}
