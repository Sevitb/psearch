<?php

declare(strict_types=1);

namespace Sevit\Psearch\Cache\Driver;

use RuntimeException;

final readonly class IpcCache
{
    public function remember(string $filePath, string $projectId): void
    {
        $systemKey = ftok($filePath, $projectId);

        if(!$sharedMemoryFragment = shm_attach($systemKey, 10_000_000)) {
            $err = error_get_last();
            throw new RuntimeException('Не удалось кешировать значения файла ' . $filePath . ': ' . $err['message']);
        }

        shm_put_var($sharedMemoryFragment, );
    }
}