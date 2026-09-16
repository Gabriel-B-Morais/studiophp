<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Tests\Unit;

use GabrielBMorais\Studio\Compiler\SourceFile;
use GabrielBMorais\Studio\Runtime\StudioRouteMapper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StudioRouteMapperTest extends TestCase
{
    #[Test]
    public function it_derives_routes_names_and_views_from_source_paths(): void
    {
        $mapper = new StudioRouteMapper();
        $source = new SourceFile(
            absolutePath: '/app/resources/studio/users/index.blade.php',
            relativePath: 'users/index.blade.php',
            contents: '',
            hash: hash('sha256', ''),
        );

        self::assertSame('/users', $mapper->uriFor($source));
        self::assertSame('studio.users.index', $mapper->nameFor($source));
        self::assertSame('users.index', $mapper->viewFor($source));
    }

    #[Test]
    public function it_maps_the_root_index_and_supports_a_route_prefix(): void
    {
        $mapper = new StudioRouteMapper(routePrefix: 'admin');
        $source = new SourceFile(
            absolutePath: '/app/resources/studio/index.blade.php',
            relativePath: 'index.blade.php',
            contents: '',
            hash: hash('sha256', ''),
        );

        self::assertSame('/admin', $mapper->uriFor($source));
        self::assertSame('studio.index', $mapper->nameFor($source));
        self::assertSame('index', $mapper->viewFor($source));
    }
}
