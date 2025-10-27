<?php

declare(strict_types=1);

namespace Sevit\Psearch\Indexer;

use Sevit\Psearch\Indexer\Tokenizers\TokenCollection;

final readonly class Document
{
    public function __construct(
        private int $id,
        private string $rawData,
        private TokenCollection $tokens,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRawData(): string
    {
        return $this->rawData;
    }

    public function getTokens(): TokenCollection
    {
        return $this->tokens;
    }
}