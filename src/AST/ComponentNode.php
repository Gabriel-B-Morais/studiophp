<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\AST;

final class ComponentNode
{
  /** @var list<AttributeNode> */
  private array $attributes;

  /** @var list<ComponentNode> */
  private array $children = [];

  /**
   * @param list<AttributeNode> $attributes
   */
  public function __construct(
    public readonly string $name,
    array $attributes,
    public readonly string $file,
    public readonly int $line,
    public readonly bool $selfClosing,
  ) {
    $this->attributes = $attributes;
  }

  /** @return list<AttributeNode> */
  public function attributes(): array
  {
    return $this->attributes;
  }

  /** @return list<ComponentNode> */
  public function children(): array
  {
    return $this->children;
  }

  public function addChild(ComponentNode $child): void
  {
    $this->children[] = $child;
  }

  public function attribute(string $name): ?AttributeNode
  {
    foreach ($this->attributes as $attribute) {
      if ($attribute->name === $name) {
        return $attribute;
      }
    }

    return null;
  }
}
