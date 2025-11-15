<?php

declare(strict_types=1);

namespace Sevit\Psearch;

use JsonSerializable;
use Sevit\Psearch\Indexer\Document;

final readonly class DocumentSearchResult implements JsonSerializable
{
    public function __construct(
        private float $score,
        private Document $document,
    ) {
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }


    public function jsonSerialize(): array
    {
        return [
            'score' => $this->getScore(),
            'document' => $this->getDocument(),
        ];
    }
}