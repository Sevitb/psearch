<?php

declare(strict_types=1);

namespace Sevit\Psearch\Storage\BinaryStorage;

use Exception;
use Sevit\Psearch\Indexer\Document;

final readonly class BinaryWriter
{
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
            $value = mb_convert_encoding($value, 'UTF-8', mb_detect_encoding($value));
            fwrite($file, pack('C', strlen($key)));
            fwrite($file, $key);
            fwrite($file, pack('V', strlen($value)));
            fwrite($file, $value);
        }
        fwrite($file, pack('C', 0));
    }
}