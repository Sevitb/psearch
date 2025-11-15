<?php

declare(strict_types=1);

namespace Sevit\Psearch\Indexer;

use JsonSerializable;

final readonly class Document implements JsonSerializable
{
    public function __construct(
        private int $id,
        private array $rawData,
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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'rawData' => $this->getRawData(),
        ];
    }
}