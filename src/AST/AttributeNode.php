<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\AST;

final readonly class AttributeNode
{
  public function __construct(
    public string $name,
    public ?string $value,
    public bool $dynamic,
    public bool $boolean,
    public int $line,
  ) {}
}
