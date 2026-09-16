<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Runtime;

use GabrielBMorais\Studio\Compiler\SemanticAnalyzer;
use GabrielBMorais\Studio\Compiler\SourceFile;
use GabrielBMorais\Studio\Compiler\StudioParser;

final readonly class StudioBladePrecompiler
{
  public function __construct(
    private StudioParser $parser,
    private SemanticAnalyzer $analyzer,
    private StudioTagTransformer $transformer,
    private string $sourcePath,
  ) {}

  public function compile(string $contents, ?string $absolutePath = null): string
  {
    if (! str_contains($contents, '<studio:') && ! str_contains($contents, '</studio:')) {
      return $contents;
    }

    $source = new SourceFile(
      absolutePath: $absolutePath ?? '<inline>',
      relativePath: $this->relativePath($absolutePath),
      contents: $contents,
      hash: hash('sha256', $contents),
    );

    // DEV and build share the exact same parser + semantic contract.
    $document = $this->parser->parse($source);
    $this->analyzer->analyze($document);

    return $this->transformer->transform($contents);
  }

  private function relativePath(?string $absolutePath): string
  {
    if ($absolutePath === null || $absolutePath === '') {
      return '<inline>';
    }

    $path = str_replace('\\', '/', $absolutePath);
    $sourcePath = rtrim(str_replace('\\', '/', $this->sourcePath), '/');

    if ($sourcePath !== '' && str_starts_with($path, $sourcePath . '/')) {
      return substr($path, strlen($sourcePath) + 1);
    }

    return $path;
  }
}
