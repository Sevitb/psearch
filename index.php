<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

use Sevit\Psearch\Configuration;
use Sevit\Psearch\Indexer\Parser;
use Sevit\Psearch\Indexer\Tokenizers\WhitespaceTokenizer;
use Sevit\Psearch\Search;
use Sevit\Psearch\Storage\BinaryStorage\BinaryStorage;

//$indexer = new Indexer(
//    new Parser(new WhitespaceTokenizer()),
//    new BinaryStorage(
//        new Configuration()
//    ),
//);

$searcher = new Search(
    new Parser(new WhitespaceTokenizer()),
    new BinaryStorage(
        new Configuration()
    ),
);

var_dump($searcher->search('Как твои дела?'));
die();

var_dump($indexer->index('Привет! Как твои дела? У меня дела хорошо.'));
die;
