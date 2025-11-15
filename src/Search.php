<?php

declare(strict_types=1);

namespace Sevit\Psearch;

use Sevit\Psearch\Indexer\Parser;
use Sevit\Psearch\Storage\BinaryStorage\BinaryStorage;

final readonly class Search
{
    public function __construct(
        private Parser $parser,
        private BinaryStorage $storage,
    ) {
    }

    public function search(string $query): SearchResult
    {
        $tokenCollection = $this->parser->parse($query);

        $invertedIndex = $this->storage->readInvertedIndex();
        $allDocuments = $this->storage->readAllDocuments();
        $allDocumentsCount = count($allDocuments);

        $documents = [];
        $tokensIdf = [];
        foreach ($tokenCollection->getAll() as $token) {
            if (!$tokenDocuments = $invertedIndex->getTokenDocuments($token)) {
                continue;
            }
            $tokensIdf[$token] = log($allDocumentsCount / count($tokenDocuments));
            foreach ($tokenDocuments as $documentId => $frequency) {
                $documents[$token][$documentId] = $frequency;
            }
        }

        $tfIdf = [];
        foreach ($documents as $token => $tokenDocuments) {
            foreach ($tokenDocuments as $documentId => $frequency) {
                if (!isset($tfIdf[$documentId])) {
                    $tfIdf[$documentId] = 0;
                }
                $tfIdf[$documentId] += $tokensIdf[$token] * $frequency;
            }
        }

        $resultDocuments = [];
        foreach ($tfIdf as $documentId => $frequency) {
            $resultDocuments[] = new DocumentSearchResult($frequency, $this->storage->getDocumentById($documentId));
        }

        return new SearchResult($resultDocuments);
    }
}