<?php

declare(strict_types=1);

namespace Sevit\Psearch\Indexer;

use Sevit\Psearch\Indexer\Tokenizers\TokenCollection;

final readonly class Document
{
    public function __construct(
        private int $id,
        private array $rawData,
        private TokenCollection $tokens,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array<string, string|int|float>
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }

    public function getTokens(): TokenCollection
    {
        return $this->tokens;
    }
}