<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

use Sevit\Psearch\Configuration;
use Sevit\Psearch\Indexer\Indexer;
use Sevit\Psearch\Indexer\Parser;
use Sevit\Psearch\Indexer\Tokenizers\WhitespaceTokenizer;
use Sevit\Psearch\Search;
use Sevit\Psearch\Storage\BinaryStorage\BinaryStorage;

$indexer = new Indexer(
    new Parser(new WhitespaceTokenizer()),
    new BinaryStorage(
        new Configuration()
    ),
);

//$indexer->index('Торт из шашлыков: секреты приготовления');

$searcher = new Search(
    new Parser(new WhitespaceTokenizer()),
    new BinaryStorage(
        new Configuration()
    ),
);

dump(json_decode(json_encode($searcher->search('как приготовить торт из шашлыков'), JSON_UNESCAPED_UNICODE), true));
die();
