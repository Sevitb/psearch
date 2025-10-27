<?php

declare(strict_types=1);

namespace Sevit\Psearch\Indexer\Tokenizers;

final class TokenCollection
{
    private array $tokens;

    /**
     * @param array<string, int> $tokensFrequencyMap
     */
    public function __construct(
        array $tokensFrequencyMap = [],
    ) {
        if ($tokensFrequencyMap) {
            $this->addTokens($tokensFrequencyMap);
        } else {
            $this->tokens = [];
        }
    }

    public function addTokenFrequency(string $token, int $frequency): void
    {
        $this->tokens[$token] = $frequency;
    }

    /**
     * @param array<string, int> $tokensFrequencyMap
     * @return void
     */
    private function addTokens(array $tokensFrequencyMap): void
    {
        foreach ($tokensFrequencyMap as $token => $frequency) {
            $this->addTokenFrequency($token, $frequency);
        }
    }

    public function getTokenFrequency(string $token): int
    {
        return $this->tokens[$token] ?? 0;
    }

    /**
     * @return array<string>
     */
    public function getAll(): array
    {
        return array_keys($this->tokens);
    }
}