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
            tokens: $tokensCollection,
        );
        
        $this->storage->writeDocument($document);

        var_dump($tokensCollection);
        die;
    }

    private function indexDocument(Document $document): IndexResult
    {

    }

    private function getLastDocumentId(): int
    {
        return $this->storage->getLastDocumentId();
    }
}