<?php

declare(strict_types=1);

namespace Sevit\Psearch\Indexer;

final class InvertedIndex
{
    private array $index = [];

    public function addTokenDocument(string $token, int $documentId, int $frequency): void
    {
        $this->index[$token][$documentId] = $frequency;
    }
}