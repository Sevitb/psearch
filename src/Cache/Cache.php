<?php

declare(strict_types=1);

namespace Sevit\Psearch\Cache;

final readonly class Cache
{
    public function __construct(
        private Configuration $configuration,
    ) {
    }
}