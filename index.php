<?php

declare(strict_types = 1);

use App\UriParser;

require __DIR__ . '/vendor/autoload.php';

$exampleUri = 'https://user:pass@example.org:80/path/123?search=baz#bar';

$uriParser = new UriParser();
/** @var \Psr\Http\Message\UriInterface $parsedUri */
$parsedUri = $uriParser->parse($exampleUri);
