<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Compiler;

use Illuminate\Filesystem\Filesystem;

final readonly class SourceScanner
{
  public function __construct(
    private Filesystem $files,
    private string $sourcePath,
  ) {}

  /** @return list<SourceFile> */
  public function scan(): array
  {
    if (! $this->files->isDirectory($this->sourcePath)) {
      return [];
    }

    $sources = [];

    foreach ($this->files->allFiles($this->sourcePath) as $file) {
      if (! str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
      }

      $absolutePath = $file->getPathname();
      $relativePath = str_replace('\\', '/', ltrim(substr($absolutePath, strlen($this->sourcePath)), DIRECTORY_SEPARATOR));
      $contents = $this->files->get($absolutePath);

      $sources[] = new SourceFile(
        absolutePath: $absolutePath,
        relativePath: $relativePath,
        contents: $contents,
        hash: hash('sha256', $contents),
      );
    }

    usort($sources, static fn(SourceFile $a, SourceFile $b): int => $a->relativePath <=> $b->relativePath);

    return $sources;
  }
}
