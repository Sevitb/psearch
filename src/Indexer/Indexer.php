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
        $tokensCollection = $this->parser->parse($text);

        $document = new Document(
            id: (int)$this->storage->getLastDocumentId() + 1,
            rawData: ['text' => $text],
        );
        
        $this->storage->writeDocument($document);

        $invertedIndex = new InvertedIndex();

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