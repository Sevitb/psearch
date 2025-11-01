<?php

declare(strict_types=1);

namespace Sevit\Psearch\Storage\BinaryStorage;

use Exception;
use Sevit\Psearch\Configuration;
use Sevit\Psearch\Indexer\Document;
use Sevit\Psearch\Indexer\InvertedIndex;

final readonly class BinaryStorage
{
    public function __construct(
        private Configuration $configuration,
    ) {
    }

    public function getLastDocumentId(): ?int
    {
        return null;
    }

    public function writeDocument(Document $document): void
    {
        $context = $this->configuration->get('storage', 'context');
        $fileName = $this->configuration->get('storage', 'db_file_name') . '.bin';
        $fullPath = $context . '/' . $fileName;
        if (!$file = fopen($fullPath, 'wb')) {
            throw new Exception("Не удалось открыть файл $fullPath для записи: " . error_get_last()['message']);
        }

        fwrite($file, pack('V', $document->getId()));
        foreach ($document->getRawData() as $key => $value) {
            $value = mb_convert_encoding($value, 'UTF-16', mb_detect_encoding($value));
            fwrite($file, pack('C', strlen($key)));
            fwrite($file, $key);
            fwrite($file, pack('V', strlen($value)));
            fwrite($file, $value);
        }
        fwrite($file, pack('C', 0));
    }

    private function packDocument(Document $document): void
    {

    }

    /**
     * @param array<Document> $documents
     * @return void
     * @throws Exception
     */
    public function writeDocuments(array $documents): void
    {
        $file = fopen($this->storageContext, 'wb');
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

    private function readOffsetIndex(): array
    {

    }
}