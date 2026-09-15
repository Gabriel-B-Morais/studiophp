<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Contracts;

use GabrielBMorais\Studio\AST\ComponentNode;

interface ComponentDefinition
{
  public function name(): string;

  public function validate(ComponentNode $node): void;
}
