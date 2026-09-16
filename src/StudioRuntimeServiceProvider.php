<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio;

use GabrielBMorais\Studio\Compiler\SemanticAnalyzer;
use GabrielBMorais\Studio\Compiler\SourceScanner;
use GabrielBMorais\Studio\Compiler\StudioParser;
use GabrielBMorais\Studio\Runtime\ComponentViewResolver;
use GabrielBMorais\Studio\Runtime\StudioBladePrecompiler;
use GabrielBMorais\Studio\Runtime\StudioRouteMapper;
use GabrielBMorais\Studio\Runtime\StudioRouteRegistrar;
use GabrielBMorais\Studio\Runtime\StudioTagTransformer;
use GabrielBMorais\Studio\View\Components\RuntimeComponent;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

final class StudioRuntimeServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    $this->app->singleton(StudioTagTransformer::class);
    $this->app->singleton(ComponentViewResolver::class);

    $this->app->singleton(StudioRouteMapper::class, fn(): StudioRouteMapper => new StudioRouteMapper(
      routePrefix: (string) config('studio.runtime.route_prefix', ''),
      routeNamePrefix: (string) config('studio.runtime.route_name_prefix', 'studio.'),
    ));

    $this->app->singleton(StudioBladePrecompiler::class, fn($app): StudioBladePrecompiler => new StudioBladePrecompiler(
      parser: $app->make(StudioParser::class),
      analyzer: $app->make(SemanticAnalyzer::class),
      transformer: $app->make(StudioTagTransformer::class),
      sourcePath: (string) config('studio.source_path'),
    ));

    $this->app->singleton(StudioRouteRegistrar::class, fn($app): StudioRouteRegistrar => new StudioRouteRegistrar(
      router: $app->make(Router::class),
      scanner: $app->make(SourceScanner::class),
      mapper: $app->make(StudioRouteMapper::class),
    ));
  }

  public function boot(): void
  {
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'studio');
    $this->loadViewsFrom((string) config('studio.source_path'), 'studio-app');

    $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade): void {
      $blade->component(RuntimeComponent::class, 'studio-runtime');

      if (! $this->runtimeEnabled()) {
        return;
      }

      $precompiler = $this->app->make(StudioBladePrecompiler::class);

      $blade->prepareStringsForCompilationUsing(
        static fn(string $value): string => $precompiler->compile($value, $blade->getPath()),
      );
    });

    if ($this->runtimeEnabled() && (bool) config('studio.runtime.routes', true)) {
      $this->app->booted(function (): void {
        $this->app->make(StudioRouteRegistrar::class)->register();
      });
    }
  }

  private function runtimeEnabled(): bool
  {
    return (bool) config('studio.runtime.enabled', ! $this->app->environment('production'));
  }
}
