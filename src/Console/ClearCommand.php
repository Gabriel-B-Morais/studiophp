<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Console;

use GabrielBMorais\Studio\Runtime\ManifestStore;
use Illuminate\Console\Command;

final class ClearCommand extends Command
{
  protected $signature = 'studio:clear';

  protected $description = 'Clear Studio runtime and compiler cache.';

  public function __construct(private readonly ManifestStore $store)
  {
    parent::__construct();
  }

  public function handle(): int
  {
    $this->store->clear();
    $this->components->info('Studio cache cleared.');

    return self::SUCCESS;
  }
}
