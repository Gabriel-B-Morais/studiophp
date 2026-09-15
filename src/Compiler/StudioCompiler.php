<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Compiler;

use GabrielBMorais\Studio\IR\FileIR;
use GabrielBMorais\Studio\IR\StudioManifest;

final readonly class StudioCompiler
{
  public function __construct(
    private SourceScanner $scanner,
    private StudioParser $parser,
    private SemanticAnalyzer $analyzer,
    private int $irVersion,
  ) {}

  public function compile(): StudioManifest
  {
    $files = [];

    foreach ($this->scanner->scan() as $source) {
      $document = $this->parser->parse($source);

      $files[] = new FileIR(
        path: $source->relativePath,
        hash: $source->hash,
        components: $this->analyzer->analyze($document),
      );
    }

    return new StudioManifest($this->irVersion, $files);
  }
}
