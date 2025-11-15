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

    public function writeInvertedIndex(InvertedIndex $index): void
    {
        $file = $this->openIndexFile('wb');
        foreach ($index->getAllTokens() as $token) {
            fwrite($file, pack('C', strlen($token)));
            fwrite($file, $token);
            $documents = $index->getTokenDocuments($token);
            fwrite($file, pack('C', count($documents)));
            foreach ($documents as $documentId => $frequency) {
                fwrite($file, pack('V', $documentId));
                fwrite($file, pack('C', $frequency));
            }
        }
        fclose($file);
    }

    public function readInvertedIndex(): InvertedIndex
    {
        $file = $this->openIndexFile('rb');

        $index = new InvertedIndex();
        while (!feof($file)) {
            $tokenLengthBytes = fread($file, 1);
            if (!$tokenLengthBytes) {
                break;
            }
            $tokenLength = unpack('C', $tokenLengthBytes)[1];
            $token = fread($file, $tokenLength);

            $docsCountByte = fread($file, 1);
            $docsCount = unpack('C', $docsCountByte)[1];
            for ($i = 0; $i < $docsCount; $i++) {
                $docIdBytes = fread($file, 4);
                $docId = unpack('V', $docIdBytes)[1];
                $docTokenFrequencyByte = fread($file, 1);
                $docTokenFrequency = unpack('C', $docTokenFrequencyByte)[1];
                $index->addTokenDocument($token, $docId, $docTokenFrequency);
            }
        }
        fclose($file);

        return $index;
    }

    public function writeDocument(Document $document): void
    {
        $file = $this->openDocumentsFile('wb');
        fwrite($file, pack('V', $document->getId()));
        foreach ($document->getRawData() as $key => $value) {
            $value = mb_convert_encoding($value, 'UTF-8', mb_detect_encoding($value));
            fwrite($file, pack('C', strlen($key)));
            fwrite($file, $key);
            fwrite($file, pack('V', strlen($value)));
            fwrite($file, $value);
        }
        fwrite($file, pack('C', 0));
        fclose($file);
    }

    public function readAllDocuments(): array
    {
        $file = $this->openDocumentsFile('rb');

        $documents = [];
        while (!feof($file)) {
            $docIdBytes = fread($file, 4);

            if ($docIdBytes === '') {
                break;
            }

            $docId = unpack('V', $docIdBytes)[1];

            $rawData = [];
            while (true) {
                $fieldNameLengthBytes = fread($file, 1);

                if ($fieldNameLengthBytes === '') {
                    break;
                }

                $fieldNameLength = unpack('C', $fieldNameLengthBytes)[1];

                if (!$fieldNameLength) {
                    break;
                }

                $field = fread($file, $fieldNameLength);

                $valueLengthBytes = fread($file, 4);
                $valueLength = unpack('V', $valueLengthBytes)[1];
                $value = fread($file, $valueLength);

                $rawData[$field] = $value;
            }

            $documents[$docId] = new Document(
                $docId,
                $rawData,
            );
        }

        return $documents;
    }

    public function getDocumentById(int $id): ?Document
    {
        $file = $this->openDocumentsFile('rb');

        $documents = [];
        while (!feof($file)) {
            $docIdBytes = fread($file, 4);

            if ($docIdBytes === '') {
                break;
            }

            $docId = unpack('V', $docIdBytes)[1];

            $rawData = [];
            while (true) {
                $fieldNameLengthBytes = fread($file, 1);

                if ($fieldNameLengthBytes === '') {
                    break;
                }

                $fieldNameLength = unpack('C', $fieldNameLengthBytes)[1];

                if (!$fieldNameLength) {
                    break;
                }

                $field = fread($file, $fieldNameLength);

                $valueLengthBytes = fread($file, 4);
                $valueLength = unpack('V', $valueLengthBytes)[1];
                $value = fread($file, $valueLength);

                $rawData[$field] = $value;
            }

            $documents[$docId] = new Document(
                $docId,
                $rawData,
            );
        }
        
        return $documents[$id] ?? null;
    }

    private function openDocumentsFile(string $mode): mixed
    {
        $context = $this->configuration->get('storage', 'context');
        $fileName = $this->configuration->get('storage', 'db_file_name') . '.bin';
        $fullPath = $context . '/' . $fileName;
        if (!$file = fopen($fullPath, $mode)) {
            throw new Exception("Не удалось открыть файл $fullPath для записи: " . error_get_last()['message']);
        }

        return $file;
    }

    private function openIndexFile(string $mode): mixed
    {
        $context = $this->configuration->get('storage', 'context');
        $fileName = $this->configuration->get('storage', 'index_file_name') . '.bin';
        $fullPath = $context . '/' . $fileName;
        if (!$file = fopen($fullPath, $mode)) {
            throw new Exception("Не удалось открыть файл $fullPath для записи: " . error_get_last()['message']);
        }

        return $file;
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