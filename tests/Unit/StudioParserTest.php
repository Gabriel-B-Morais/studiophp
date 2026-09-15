<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Tests\Unit;

use GabrielBMorais\Studio\Compiler\SourceFile;
use GabrielBMorais\Studio\Compiler\StudioParser;
use PHPUnit\Framework\TestCase;

final class StudioParserTest extends TestCase
{
  public function test_it_parses_nested_components_and_attributes(): void
  {
    $source = new SourceFile(
      absolutePath: '/tmp/users/index.blade.php',
      relativePath: 'users/index.blade.php',
      contents: <<<'BLADE'
<studio:layout name="app">
    <studio:resource model="User">
        <studio:input name="email" type="email" required :disabled="$locked" />
    </studio:resource>
</studio:layout>
BLADE,
      hash: 'test',
    );

    $document = (new StudioParser())->parse($source);

    self::assertCount(1, $document->children);
    self::assertSame('layout', $document->children[0]->name);
    self::assertSame('resource', $document->children[0]->children()[0]->name);

    $input = $document->children[0]->children()[0]->children()[0];

    self::assertSame('input', $input->name);
    self::assertSame('email', $input->attribute('name')?->value);
    self::assertTrue($input->attribute('required')?->boolean ?? false);
    self::assertTrue($input->attribute('disabled')?->dynamic ?? false);
    self::assertSame('$locked', $input->attribute('disabled')?->value);
  }
}
