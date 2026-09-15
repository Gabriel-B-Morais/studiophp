<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Console;

use GabrielBMorais\Studio\Compiler\StudioCompiler;
use GabrielBMorais\Studio\Exceptions\StudioCompileException;
use GabrielBMorais\Studio\Runtime\ManifestStore;
use Illuminate\Console\Command;
use Throwable;

final class BuildCommand extends Command
{
  protected $signature = 'studio:build {--check : Validate sources without writing the manifest}';

  protected $description = 'Compile Studio sources into the versioned intermediate representation.';

  public function __construct(
    private readonly StudioCompiler $compiler,
    private readonly ManifestStore $store,
  ) {
    parent::__construct();
  }

  public function handle(): int
  {
    try {
      $manifest = $this->compiler->compile();

      if (! $this->option('check')) {
        $this->store->write($manifest);
      }

      $this->components->info(sprintf(
        'Studio %s: %d source file(s), IR v%d.',
        $this->option('check') ? 'check passed' : 'build completed',
        count($manifest->files),
        $manifest->irVersion,
      ));

      return self::SUCCESS;
    } catch (StudioCompileException $exception) {
      $this->components->error($exception->getMessage());

      return self::FAILURE;
    } catch (Throwable $exception) {
      $this->components->error('Studio build failed: ' . $exception->getMessage());

      return self::FAILURE;
    }
  }
}
