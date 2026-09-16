<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Tests\Unit;

use GabrielBMorais\Studio\Runtime\StudioTagTransformer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StudioTagTransformerTest extends TestCase
{
    #[Test]
    public function it_lowers_nested_studio_tags_to_the_runtime_component(): void
    {
        $source = <<<'BLADE'
<studio:layout name="app">
    <studio:form>
        <studio:input name="email" :disabled="$locked" required />
    </studio:form>
</studio:layout>
BLADE;

        $result = (new StudioTagTransformer())->transform($source);

        self::assertStringContainsString('<x-studio-runtime studio-component="layout" name="app">', $result);
        self::assertStringContainsString('<x-studio-runtime studio-component="input" name="email" :disabled="$locked" required />', $result);
        self::assertSame(2, substr_count($result, '</x-studio-runtime>'));
    }

    #[Test]
    public function it_does_not_lower_studio_markup_inside_verbatim_blocks_or_comments(): void
    {
        $source = <<<'BLADE'
@verbatim
<studio:input name="ignored" />
@endverbatim
{{-- <studio:input name="ignored-too" /> --}}
<studio:input name="real" />
BLADE;

        $result = (new StudioTagTransformer())->transform($source);

        self::assertStringContainsString('<studio:input name="ignored" />', $result);
        self::assertStringContainsString('<studio:input name="ignored-too" />', $result);
        self::assertStringContainsString('<x-studio-runtime studio-component="input" name="real" />', $result);
    }
}
