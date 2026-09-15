<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio;

use GabrielBMorais\Studio\Compiler\SemanticAnalyzer;
use GabrielBMorais\Studio\Compiler\SourceScanner;
use GabrielBMorais\Studio\Compiler\StudioCompiler;
use GabrielBMorais\Studio\Compiler\StudioParser;
use GabrielBMorais\Studio\Console\BuildCommand;
use GabrielBMorais\Studio\Console\ClearCommand;
use GabrielBMorais\Studio\Registry\ComponentRegistry;
use GabrielBMorais\Studio\Runtime\ManifestStore;
use GabrielBMorais\Studio\Support\BuiltInComponents;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

final class StudioServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    $this->mergeConfigFrom(__DIR__ . '/../config/studio.php', 'studio');

    $this->app->singleton(ComponentRegistry::class, static function (): ComponentRegistry {
      $registry = new ComponentRegistry();
      BuiltInComponents::register($registry);

      return $registry;
    });

    $this->app->singleton(SourceScanner::class, fn($app): SourceScanner => new SourceScanner(
      files: $app->make(Filesystem::class),
      sourcePath: (string) config('studio.source_path'),
    ));

    $this->app->singleton(StudioParser::class);

    $this->app->singleton(SemanticAnalyzer::class, fn($app): SemanticAnalyzer => new SemanticAnalyzer(
      registry: $app->make(ComponentRegistry::class),
      strict: (bool) config('studio.strict', true),
    ));

    $this->app->singleton(StudioCompiler::class, fn($app): StudioCompiler => new StudioCompiler(
      scanner: $app->make(SourceScanner::class),
      parser: $app->make(StudioParser::class),
      analyzer: $app->make(SemanticAnalyzer::class),
      irVersion: (int) config('studio.ir_version', 1),
    ));

    $this->app->singleton(ManifestStore::class, fn($app): ManifestStore => new ManifestStore(
      files: $app->make(Filesystem::class),
      cachePath: (string) config('studio.cache_path'),
      manifestPath: (string) config('studio.manifest_path'),
    ));
  }

  public function boot(): void
  {
    $this->publishes([
      __DIR__ . '/../config/studio.php' => config_path('studio.php'),
    ], 'studio-config');

    if ($this->app->runningInConsole()) {
      $this->commands([
        BuildCommand::class,
        ClearCommand::class,
      ]);
    }
  }
}
