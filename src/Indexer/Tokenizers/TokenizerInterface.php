<?php

declare(strict_types=1);

namespace Sevit\Psearch\Indexer\Tokenizers;

interface TokenizerInterface
{
    public function tokenize(string $text): array;
}