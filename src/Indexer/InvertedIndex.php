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

    public function getTokenDocuments(string $token): ?array
    {
        return $this->index[$token];
    }

    public function getTokenDocumentsCount(string $token): int
    {
        return count($this->index[$token] ?? []);
    }

    public function getAllTokens(): array
    {
        return array_keys($this->index);
    }
}