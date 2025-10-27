<?php

declare(strict_types=1);

namespace Sevit\Psearch\Indexer\Tokenizers;

final readonly class WhitespaceTokenizer implements TokenizerInterface
{
    public function tokenize(string $text): array
    {
        return (!$tokens = \preg_split('/\s+/', $text))
            ? []
            : $tokens;
    }
}