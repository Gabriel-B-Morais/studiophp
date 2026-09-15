<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Registry;

use GabrielBMorais\Studio\Contracts\ComponentDefinition;

final class ComponentRegistry
{
  /** @var array<string, ComponentDefinition> */
  private array $definitions = [];

  public function register(ComponentDefinition $definition): self
  {
    $this->definitions[$definition->name()] = $definition;

    return $this;
  }

  public function resolve(string $name): ?ComponentDefinition
  {
    if (isset($this->definitions[$name])) {
      return $this->definitions[$name];
    }

    foreach ($this->definitions as $registeredName => $definition) {
      if (! str_ends_with($registeredName, '*')) {
        continue;
      }

      if (str_starts_with($name, substr($registeredName, 0, -1))) {
        return $definition;
      }
    }

    return null;
  }

  /** @return list<string> */
  public function names(): array
  {
    $names = array_keys($this->definitions);
    sort($names);

    return $names;
  }
}
