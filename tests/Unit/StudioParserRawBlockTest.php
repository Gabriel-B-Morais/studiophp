<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Tests\Unit;

use GabrielBMorais\Studio\Compiler\SourceFile;
use GabrielBMorais\Studio\Compiler\StudioParser;
use PHPUnit\Framework\TestCase;

final class StudioParserRawBlockTest extends TestCase
{
  public function test_it_ignores_studio_markup_inside_verbatim_and_php_blocks(): void
  {
    $source = new SourceFile(
      absolutePath: '/tmp/index.blade.php',
      relativePath: 'index.blade.php',
      contents: <<<'BLADE'
@verbatim
<studio:input name="ignored" />
@endverbatim
@php
$example = '<studio:input name="also-ignored" />';
@endphp
<studio:input name="real" />
BLADE,
      hash: 'test',
    );

    $document = (new StudioParser())->parse($source);

    self::assertCount(1, $document->children);
    self::assertSame('real', $document->children[0]->attribute('name')?->value);
  }
}
