<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Registry;

use GabrielBMorais\Studio\AST\ComponentNode;
use GabrielBMorais\Studio\Contracts\ComponentDefinition;
use GabrielBMorais\Studio\Exceptions\StudioCompileException;

final readonly class GenericComponentDefinition implements ComponentDefinition
{
  /**
   * @param list<string> $requiredAttributes
   * @param list<string>|null $allowedChildren
   */
  public function __construct(
    private string $componentName,
    private array $requiredAttributes = [],
    private ?array $allowedChildren = null,
  ) {}

  public function name(): string
  {
    return $this->componentName;
  }

  public function validate(ComponentNode $node): void
  {
    foreach ($this->requiredAttributes as $required) {
      if ($node->attribute($required) === null) {
        throw new StudioCompileException(
          sprintf('requires attribute [%s].', $required),
          $node->file,
          $node->line,
          $node->name,
        );
      }
    }

    if ($this->allowedChildren === null) {
      return;
    }

    foreach ($node->children() as $child) {
      if (! $this->matchesAllowedChild($child->name)) {
        throw new StudioCompileException(
          sprintf('does not allow child <studio:%s>.', $child->name),
          $child->file,
          $child->line,
          $node->name,
        );
      }
    }
  }

  private function matchesAllowedChild(string $name): bool
  {
    foreach ($this->allowedChildren ?? [] as $allowed) {
      if ($allowed === $name) {
        return true;
      }

      if (str_ends_with($allowed, '*') && str_starts_with($name, substr($allowed, 0, -1))) {
        return true;
      }
    }

    return false;
  }
}
