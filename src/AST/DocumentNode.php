<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\AST;

final readonly class DocumentNode
{
  /**
   * @param list<ComponentNode> $children
   */
  public function __construct(
    public string $file,
    public array $children,
  ) {}
}
