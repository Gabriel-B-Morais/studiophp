<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Tests\Unit;

use GabrielBMorais\Studio\Compiler\SemanticAnalyzer;
use GabrielBMorais\Studio\Compiler\SourceFile;
use GabrielBMorais\Studio\Compiler\StudioParser;
use GabrielBMorais\Studio\Exceptions\StudioCompileException;
use GabrielBMorais\Studio\Registry\ComponentRegistry;
use GabrielBMorais\Studio\Support\BuiltInComponents;
use PHPUnit\Framework\TestCase;

final class StudioRuntimeSemanticTest extends TestCase
{
  public function test_studio_prefixed_attributes_are_reserved_for_runtime_internals(): void
  {
    $registry = new ComponentRegistry();
    BuiltInComponents::register($registry);

    $document = (new StudioParser())->parse(new SourceFile(
      absolutePath: '/tmp/index.blade.php',
      relativePath: 'index.blade.php',
      contents: '<studio:input name="email" studio-component="fake" />',
      hash: 'test',
    ));

    $this->expectException(StudioCompileException::class);
    $this->expectExceptionMessage('reserved for Studio internals');

    (new SemanticAnalyzer($registry))->analyze($document);
  }
}
