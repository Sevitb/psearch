<?php

declare(strict_types=1);

namespace Sevit\Psearch\Indexer;

use Sevit\Psearch\Indexer\Tokenizers\TokenCollection;
use Sevit\Psearch\Indexer\Tokenizers\TokenizerInterface;

final readonly class Parser
{
    private const array PUNCTUATION = [
        '!',
        '"',
        '#',
        '$',
        '%',
        '&',
        '\'',
        '(',
        ')',
        '*',
        '+',
        ',',
        '-',
        '.',
        '/',
        ';',
        ':',
        '<',
        '=',
        '>',
        '?',
        '@',
        '[',
        '\\',
        ']',
        '^',
        '_',
        '`',
        '{',
        '|',
        '}',
        '~',
        '—',
        '–',
        '«',
        '»',
        '„',
        '“',
        '”',
        '…',
        '‹',
        '›'
    ];

    public function __construct(
        private TokenizerInterface $tokenizer,
    ) {
    }

    public function parse(string $text): TokenCollection
    {
        $tokens = $this->tokenizer->tokenize(
            $this->clearText($text)
        );
        $tokens = $this->lowerCaseTokens($tokens);
        $tokensFrequencyMap = array_count_values($tokens);

        return new TokenCollection($tokensFrequencyMap);
    }

    private function clearText(string $text): string
    {
        return \rtrim(\str_replace(self::PUNCTUATION, ' ', $text));
    }

    private function lowerCaseTokens(array $tokens): array
    {
        return array_map(static fn (string $token) => \mb_strtolower($token), $tokens);
    }
}