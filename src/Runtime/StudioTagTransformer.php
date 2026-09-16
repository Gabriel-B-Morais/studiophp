<?php

declare(strict_types=1);

namespace GabrielBMorais\Studio\Runtime;

use RuntimeException;

final class StudioTagTransformer
{
    public function transform(string $source): string
    {
        if (! str_contains($source, '<studio:') && ! str_contains($source, '</studio:')) {
            return $source;
        }

        $length = strlen($source);
        $index = 0;
        $output = '';

        while ($index < $length) {
            if ($this->startsWith($source, $index, '{{--')) {
                [$chunk, $index] = $this->copyRawBlock($source, $index, '--}}', 'Unclosed Blade comment while lowering Studio tags.');
                $output .= $chunk;
                continue;
            }

            if ($this->startsWith($source, $index, '<!--')) {
                [$chunk, $index] = $this->copyRawBlock($source, $index, '-->', 'Unclosed HTML comment while lowering Studio tags.');
                $output .= $chunk;
                continue;
            }

            if ($this->startsWith($source, $index, '@verbatim')) {
                [$chunk, $index] = $this->copyRawBlock($source, $index, '@endverbatim', 'Unclosed @verbatim block while lowering Studio tags.');
                $output .= $chunk;
                continue;
            }

            if ($this->startsWith($source, $index, '@php') && ! $this->startsWith($source, $index, '@php(')) {
                [$chunk, $index] = $this->copyRawBlock($source, $index, '@endphp', 'Unclosed @php block while lowering Studio tags.');
                $output .= $chunk;
                continue;
            }

            if ($this->startsWith($source, $index, '</studio:')) {
                $tagEnd = $this->findTagEnd($source, $index);
                $output .= '</x-studio-runtime>';
                $index = $tagEnd + 1;
                continue;
            }

            if ($this->startsWith($source, $index, '<studio:')) {
                $nameStart = $index + strlen('<studio:');
                $cursor = $nameStart;

                while ($cursor < $length && $this->isComponentNameCharacter($source[$cursor])) {
                    $cursor++;
                }

                $name = substr($source, $nameStart, $cursor - $nameStart);

                if ($name === '') {
                    throw new RuntimeException('Studio runtime lowering encountered a component without a name.');
                }

                $tagEnd = $this->findTagEnd($source, $cursor);
                $tail = substr($source, $cursor, $tagEnd - $cursor + 1);
                $output .= '<x-studio-runtime studio-component="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'"'.$tail;
                $index = $tagEnd + 1;
                continue;
            }

            $output .= $source[$index];
            $index++;
        }

        return $output;
    }

    private function findTagEnd(string $source, int $start): int
    {
        $length = strlen($source);
        $quote = null;

        for ($index = $start; $index < $length; $index++) {
            $char = $source[$index];

            if ($quote !== null) {
                if ($char === $quote && ($index === 0 || $source[$index - 1] !== '\\')) {
                    $quote = null;
                }

                continue;
            }

            if ($char === '"' || $char === "'") {
                $quote = $char;
                continue;
            }

            if ($char === '>') {
                return $index;
            }
        }

        throw new RuntimeException('Studio runtime lowering encountered an unterminated tag.');
    }

    /** @return array{0:string,1:int} */
    private function copyRawBlock(string $source, int $start, string $terminator, string $error): array
    {
        $position = strpos($source, $terminator, $start);

        if ($position === false) {
            throw new RuntimeException($error);
        }

        $end = $position + strlen($terminator);

        return [substr($source, $start, $end - $start), $end];
    }

    private function startsWith(string $source, int $index, string $needle): bool
    {
        return substr_compare($source, $needle, $index, strlen($needle)) === 0;
    }

    private function isComponentNameCharacter(string $char): bool
    {
        return ctype_alnum($char) || $char === '.' || $char === '-' || $char === '_';
    }
}
