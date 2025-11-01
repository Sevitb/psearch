<?php

declare(strict_types=1);

namespace Sevit\Psearch;

final class Configuration
{
    private array $configuration;

    public function get(string $file, string $key, mixed $default = null): mixed
    {
        $configuration = $this->getFileConfiguration($file);
        return $configuration[$key] ?? $default;
    }

    private function getFileConfiguration(string $file): ?array
    {
        if (!isset($this->configuration[$file])) {
            $this->configuration[$file] = require "./config/$file.php";
        }

        return $this->configuration[$file];
    }
}