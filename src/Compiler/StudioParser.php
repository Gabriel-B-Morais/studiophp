<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Compiler;

use GabrielBMorais\Studio\AST\AttributeNode;
use GabrielBMorais\Studio\AST\ComponentNode;
use GabrielBMorais\Studio\AST\DocumentNode;
use GabrielBMorais\Studio\Exceptions\StudioCompileException;

final class StudioParser
{
  private string $source = '';
  private string $file = '';
  private int $length = 0;
  private int $index = 0;
  private int $line = 1;

  public function parse(SourceFile $source): DocumentNode
  {
    $this->source = $source->contents;
    $this->file = $source->relativePath;
    $this->length = strlen($this->source);
    $this->index = 0;
    $this->line = 1;

    /** @var list<ComponentNode> $roots */
    $roots = [];

    /** @var list<ComponentNode> $stack */
    $stack = [];

    while (! $this->eof()) {
      if ($this->startsWith('{{--')) {
        $this->skipUntil('--}}', 'Unclosed Blade comment.');
        continue;
      }

      if ($this->startsWith('<!--')) {
        $this->skipUntil('-->', 'Unclosed HTML comment.');
        continue;
      }

      if ($this->startsWith('</studio:')) {
        $closingLine = $this->line;
        $name = $this->parseClosingTag();
        $opened = array_pop($stack);

        if ($opened === null) {
          throw new StudioCompileException(
            message: 'Closing tag has no matching opening tag.',
            sourceFile: $this->file,
            sourceLine: $closingLine,
            component: $name,
          );
        }

        if ($opened->name !== $name) {
          throw new StudioCompileException(
            message: sprintf('Expected closing tag </studio:%s>.', $opened->name),
            sourceFile: $this->file,
            sourceLine: $closingLine,
            component: $name,
          );
        }

        continue;
      }

      if ($this->startsWith('<studio:')) {
        $node = $this->parseOpeningTag();

        if ($stack === []) {
          $roots[] = $node;
        } else {
          $stack[array_key_last($stack)]->addChild($node);
        }

        if (! $node->selfClosing) {
          $stack[] = $node;
        }

        continue;
      }

      $this->advance();
    }

    if ($stack !== []) {
      $unclosed = $stack[array_key_last($stack)];

      throw new StudioCompileException(
        message: 'Component is not closed.',
        sourceFile: $this->file,
        sourceLine: $unclosed->line,
        component: $unclosed->name,
      );
    }

    return new DocumentNode($this->file, $roots);
  }

  private function parseOpeningTag(): ComponentNode
  {
    $line = $this->line;
    $this->consumeLiteral('<studio:');
    $name = $this->readName();

    if ($name === '') {
      throw new StudioCompileException('Component name is required.', $this->file, $line);
    }

    $attributes = [];
    $selfClosing = false;

    while (! $this->eof()) {
      $this->skipWhitespace();

      if ($this->startsWith('/>')) {
        $this->consumeLiteral('/>');
        $selfClosing = true;
        break;
      }

      if ($this->startsWith('>')) {
        $this->consumeLiteral('>');
        break;
      }

      $attributes[] = $this->parseAttribute($name);
    }

    if ($this->eof() && ! $selfClosing && ($this->index === 0 || $this->source[$this->index - 1] !== '>')) {
      throw new StudioCompileException('Opening tag is not terminated.', $this->file, $line, $name);
    }

    return new ComponentNode(
      name: $name,
      attributes: $attributes,
      file: $this->file,
      line: $line,
      selfClosing: $selfClosing,
    );
  }

  private function parseClosingTag(): string
  {
    $line = $this->line;
    $this->consumeLiteral('</studio:');
    $name = $this->readName();
    $this->skipWhitespace();

    if (! $this->startsWith('>')) {
      throw new StudioCompileException('Closing tag is malformed.', $this->file, $line, $name ?: null);
    }

    $this->consumeLiteral('>');

    return $name;
  }

  private function parseAttribute(string $component): AttributeNode
  {
    $line = $this->line;
    $dynamic = false;

    if ($this->startsWith(':')) {
      $dynamic = true;
      $this->advance();
    }

    $name = $this->readAttributeName();

    if ($name === '') {
      throw new StudioCompileException('Invalid attribute syntax.', $this->file, $line, $component);
    }

    $this->skipWhitespace();

    if (! $this->startsWith('=')) {
      return new AttributeNode($name, null, $dynamic, true, $line);
    }

    $this->advance();
    $this->skipWhitespace();

    if ($this->eof()) {
      throw new StudioCompileException(sprintf('Attribute [%s] is missing a value.', $name), $this->file, $line, $component);
    }

    $value = $this->readAttributeValue($component, $name, $line);

    return new AttributeNode($name, $value, $dynamic, false, $line);
  }

  private function readAttributeValue(string $component, string $attribute, int $line): string
  {
    $quote = $this->source[$this->index];

    if ($quote === '"' || $quote === "'") {
      $this->advance();
      $value = '';

      while (! $this->eof() && $this->source[$this->index] !== $quote) {
        $value .= $this->source[$this->index];
        $this->advance();
      }

      if ($this->eof()) {
        throw new StudioCompileException(
          sprintf('Attribute [%s] has an unclosed quoted value.', $attribute),
          $this->file,
          $line,
          $component,
        );
      }

      $this->advance();

      return $value;
    }

    $value = '';

    while (! $this->eof()) {
      $char = $this->source[$this->index];

      if (ctype_space($char) || $char === '>') {
        break;
      }

      if ($char === '/' && $this->startsWith('/>')) {
        break;
      }

      $value .= $char;
      $this->advance();
    }

    if ($value === '') {
      throw new StudioCompileException(
        sprintf('Attribute [%s] is missing a value.', $attribute),
        $this->file,
        $line,
        $component,
      );
    }

    return $value;
  }

  private function readName(): string
  {
    $name = '';

    while (! $this->eof()) {
      $char = $this->source[$this->index];

      if (! ctype_alnum($char) && $char !== '.' && $char !== '-' && $char !== '_') {
        break;
      }

      $name .= $char;
      $this->advance();
    }

    return $name;
  }

  private function readAttributeName(): string
  {
    $name = '';

    while (! $this->eof()) {
      $char = $this->source[$this->index];

      if (ctype_space($char) || $char === '=' || $char === '>' || ($char === '/' && $this->startsWith('/>'))) {
        break;
      }

      $name .= $char;
      $this->advance();
    }

    return $name;
  }

  private function skipWhitespace(): void
  {
    while (! $this->eof() && ctype_space($this->source[$this->index])) {
      $this->advance();
    }
  }

  private function skipUntil(string $terminator, string $error): void
  {
    while (! $this->eof() && ! $this->startsWith($terminator)) {
      $this->advance();
    }

    if ($this->eof()) {
      throw new StudioCompileException($error, $this->file, $this->line);
    }

    $this->consumeLiteral($terminator);
  }

  private function consumeLiteral(string $literal): void
  {
    $length = strlen($literal);

    for ($offset = 0; $offset < $length; $offset++) {
      if ($this->eof() || $this->source[$this->index] !== $literal[$offset]) {
        throw new StudioCompileException('Unexpected parser state.', $this->file, $this->line);
      }

      $this->advance();
    }
  }

  private function startsWith(string $value): bool
  {
    return substr_compare($this->source, $value, $this->index, strlen($value)) === 0;
  }

  private function advance(): void
  {
    if ($this->eof()) {
      return;
    }

    if ($this->source[$this->index] === "\n") {
      $this->line++;
    }

    $this->index++;
  }

  private function eof(): bool
  {
    return $this->index >= $this->length;
  }
}
