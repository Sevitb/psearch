<?php

declare(strict_types=1);

namespace Sevit\Psearch\Storage;

use Exception;
use Sevit\Psearch\Indexer\Document;
use Sevit\Psearch\Indexer\InvertedIndex;

final readonly class BinaryStorage
{
    public function __construct(
        private string $storagePath,
    ) {
    }

    public function getLastDocumentId(): ?int
    {

    }

    /**
     * @param array<Document> $documents
     * @return void
     * @throws Exception
     */
    public function writeDocuments(array $documents): void
    {
        $file = fopen($this->storagePath, 'wb');
        if (!$file) {
            throw new Exception('Не удалось открыть файл для записи');
        }

        $invertedIndex = new InvertedIndex();
        /** @var Document $document */
        foreach ($documents as $document) {
            foreach ($document->getTokens()->getAll() as $token) {
                $documentFrequency = $document->getTokens()->getTokenFrequency($token);
                $invertedIndex->addTokenDocument($token, $document->getId(), $documentFrequency);
            }
        }

        foreach ($documents as $document) {
            $this->packDocument($document);
        }

    }

    private function packDocument(Document $document): void
    {

    }

    private function readOffsetIndex(): array
    {

    }
}