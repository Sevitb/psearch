<?php

declare(strict_types=1);

namespace Sevit\Psearch\Indexer;

use Sevit\Psearch\Storage\BinaryStorage\BinaryStorage;

final readonly class Indexer
{
    public function __construct(
        private Parser $parser,
        private BinaryStorage $storage,
    ) {
    }

    public function index(string $text): IndexResult
    {
        $documents = $this->storage->readAllDocuments();
        $lastDocumentId = array_key_last($documents);

        $documents[] = $document = new Document(
            id: (int)$lastDocumentId + 1,
            rawData: ['text' => $text],
        );

        $this->storage->writeDocuments($documents);

        $invertedIndex = $this->storage->readInvertedIndex();
        $tokensCollection = $this->parser->parse($text);
        foreach ($tokensCollection->getAll() as $token) {
            $invertedIndex->addTokenDocument($token, $document->getId(), $tokensCollection->getTokenFrequency($token));
        }

        $this->storage->writeInvertedIndex($invertedIndex);

        return new IndexResult();
    }

    private function indexDocument(Document $document): IndexResult
    {

    }

    private function getLastDocumentId(): int
    {
        return $this->storage->getLastDocumentId();
    }
}