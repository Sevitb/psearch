<?php

declare(strict_types=1);

namespace Sevit\Psearch;

use JsonSerializable;

final readonly class SearchResult implements JsonSerializable
{
    public function __construct(
        private array $documents,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'documents' => $this->documents,
        ];
    }
}