<?php

declare(strict_types = 1);

namespace Tests\App;

use App\Uri;
use App\UriParser;

/**
 * Class UriParserTest
 * @package Tests\App
 */
class UriParserTest extends \PHPUnit_Framework_TestCase
{

    public function testUriParser()
    {
        $uriParser = new UriParser();
        $parsed = $uriParser->parse('https://user:pass@example.org:80/path/123?search=baz#bar');

        static::assertInstanceOf(Uri::class, $parsed);
    }
}
