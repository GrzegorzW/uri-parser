<?php

declare(strict_types = 1);

use App\UriParser;
use App\Uri;

require __DIR__ . '/vendor/autoload.php';


$exampleUri = 'https://user:pass@example.org:443/path/123?search=baz#bar';

var_dump(parse_url($exampleUri));

$parser = new UriParser();
$components = $parser->parse($exampleUri);

var_dump($components);

$uri = new Uri($components);
echo (string)$uri . "\n";
