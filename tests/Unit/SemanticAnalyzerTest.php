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

final class SemanticAnalyzerTest extends TestCase
{
  public function test_resource_requires_model_attribute(): void
  {
    $registry = new ComponentRegistry();
    BuiltInComponents::register($registry);

    $document = (new StudioParser())->parse(new SourceFile(
      absolutePath: '/tmp/index.blade.php',
      relativePath: 'index.blade.php',
      contents: '<studio:resource />',
      hash: 'test',
    ));

    $this->expectException(StudioCompileException::class);
    $this->expectExceptionMessage('requires attribute [model]');

    (new SemanticAnalyzer($registry))->analyze($document);
  }
}
