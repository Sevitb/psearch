<?php

declare(strict_types=1);

namespace Sevit\Psearch;

use Sevit\Psearch\Indexer\Parser;
use Sevit\Psearch\Storage\BinaryStorage\BinaryStorage;

final readonly class Search
{
    public function __construct(
        private Parser $parser,
        private BinaryStorage $binaryStorage,
    ) {
    }

    public function search(string $query): array
    {
        $tokenCollection = $this->parser->parse($query);

        $invertedIndex = $this->binaryStorage->readInvertedIndex();
        $allDocuments = $this->binaryStorage->readAllDocuments();
        $allDocumentsCount = count($allDocuments);

        $documents = [];
        $tokensIdf = [];
        foreach ($tokenCollection->getAll() as $token) {
            $tokenDocuments = $invertedIndex->getTokenDocuments($token);
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

        return $documents;
    }
}