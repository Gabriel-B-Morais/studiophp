<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Compiler;

use GabrielBMorais\Studio\AST\AttributeNode;
use GabrielBMorais\Studio\AST\ComponentNode;
use GabrielBMorais\Studio\AST\DocumentNode;
use GabrielBMorais\Studio\Exceptions\StudioCompileException;
use GabrielBMorais\Studio\IR\AttributeIR;
use GabrielBMorais\Studio\IR\ComponentIR;
use GabrielBMorais\Studio\Registry\ComponentRegistry;

final readonly class SemanticAnalyzer
{
  public function __construct(
    private ComponentRegistry $registry,
    private bool $strict = true,
  ) {}

  /** @return list<ComponentIR> */
  public function analyze(DocumentNode $document): array
  {
    return array_map(fn(ComponentNode $node): ComponentIR => $this->analyzeNode($node), $document->children);
  }

  private function analyzeNode(ComponentNode $node): ComponentIR
  {
    foreach ($node->attributes() as $attribute) {
      if (str_starts_with($attribute->name, 'studio-')) {
        throw new StudioCompileException(
          message: sprintf('attribute [%s] is reserved for Studio internals.', $attribute->name),
          sourceFile: $node->file,
          sourceLine: $attribute->line,
          component: $node->name,
        );
      }
    }

    $definition = $this->registry->resolve($node->name);

    if ($definition === null && $this->strict) {
      throw new StudioCompileException(
        message: 'is not registered in the Studio ComponentRegistry.',
        sourceFile: $node->file,
        sourceLine: $node->line,
        component: $node->name,
      );
    }

    $definition?->validate($node);

    return new ComponentIR(
      name: $node->name,
      line: $node->line,
      attributes: array_map(
        static fn(AttributeNode $attribute): AttributeIR => new AttributeIR(
          name: $attribute->name,
          value: $attribute->value,
          dynamic: $attribute->dynamic,
          boolean: $attribute->boolean,
        ),
        $node->attributes(),
      ),
      children: array_map(fn(ComponentNode $child): ComponentIR => $this->analyzeNode($child), $node->children()),
    );
  }
}
