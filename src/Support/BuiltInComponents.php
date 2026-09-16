<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Support;

use GabrielBMorais\Studio\Registry\ComponentRegistry;
use GabrielBMorais\Studio\Registry\GenericComponentDefinition;

final class BuiltInComponents
{
  public static function register(ComponentRegistry $registry): void
  {
    $definitions = [
      new GenericComponentDefinition('page'),
      new GenericComponentDefinition('layout'),
      new GenericComponentDefinition('section'),
      new GenericComponentDefinition('container'),
      new GenericComponentDefinition('grid'),
      new GenericComponentDefinition('stack'),
      new GenericComponentDefinition('row'),
      new GenericComponentDefinition('card'),
      new GenericComponentDefinition('resource', ['model']),
      new GenericComponentDefinition('schema'),
      new GenericComponentDefinition('field', ['name']),
      new GenericComponentDefinition('label'),
      new GenericComponentDefinition('description'),
      new GenericComponentDefinition('error'),
      new GenericComponentDefinition('form'),
      new GenericComponentDefinition('input', ['name']),
      new GenericComponentDefinition('input.*', ['name']),
      new GenericComponentDefinition('textarea', ['name']),
      new GenericComponentDefinition('select', ['name']),
      new GenericComponentDefinition('checkbox', ['name']),
      new GenericComponentDefinition('switch', ['name']),
      new GenericComponentDefinition('button'),
      new GenericComponentDefinition('alert'),
      new GenericComponentDefinition('table'),
      new GenericComponentDefinition('table.column', ['name']),
      new GenericComponentDefinition('column.*', ['name']),
      new GenericComponentDefinition('filters'),
      new GenericComponentDefinition('filter', ['name']),
      new GenericComponentDefinition('filter.*', ['name']),
      new GenericComponentDefinition('actions'),
      new GenericComponentDefinition('action', ['name']),
      new GenericComponentDefinition('action.*'),
      new GenericComponentDefinition('dashboard'),
      new GenericComponentDefinition('chart'),
    ];

    foreach ($definitions as $definition) {
      $registry->register($definition);
    }
  }
}
