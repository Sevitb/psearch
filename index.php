<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

use Sevit\Psearch\Indexer\Indexer;
use Sevit\Psearch\Indexer\Parser;
use Sevit\Psearch\Indexer\Tokenizers\WhitespaceTokenizer;

$indexer = new Indexer(
    new Parser(new WhitespaceTokenizer())
);

var_dump($indexer->index('Привет! Как твои дела? У меня дела хорошо.'));
die;
