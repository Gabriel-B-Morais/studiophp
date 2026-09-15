<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Runtime;

use GabrielBMorais\Studio\IR\StudioManifest;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;

final readonly class ManifestStore
{
  public function __construct(
    private Filesystem $files,
    private string $cachePath,
    private string $manifestPath,
  ) {}

  public function write(StudioManifest $manifest): void
  {
    $this->files->ensureDirectoryExists($this->cachePath);

    $json = json_encode(
      $manifest->toArray(),
      JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
    ) . PHP_EOL;

    $temporary = $this->manifestPath . '.tmp';
    $this->files->put($temporary, $json, true);

    if (! @rename($temporary, $this->manifestPath)) {
      $this->files->delete($temporary);
      throw new RuntimeException(sprintf('Unable to atomically write Studio manifest to [%s].', $this->manifestPath));
    }
  }

  /** @return array<string, mixed>|null */
  public function read(): ?array
  {
    if (! $this->files->exists($this->manifestPath)) {
      return null;
    }

    return json_decode($this->files->get($this->manifestPath), true, flags: JSON_THROW_ON_ERROR);
  }

  public function clear(): void
  {
    if ($this->files->isDirectory($this->cachePath)) {
      $this->files->deleteDirectory($this->cachePath);
    }
  }
}
