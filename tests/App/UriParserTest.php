<?php

declare(strict_types = 1);

namespace Tests\App;

use App\UriParser;
use InvalidArgumentException;

class UriParserTest extends TestCase
{

//'https://user:pass@example.org:80/path/123?search=baz#bar'

    public function testUriParserScheme()
    {
        $uriParser = new UriParser();
        $parsed = $uriParser->parse('https://user:pass@example.org:80/path/123?search=baz#bar');

        static::assertEquals('https', $parsed['scheme']);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUriParserSchemeInvalidChar()
    {
        $uriParser = new UriParser();
        $uriParser->parse('htt ps://user:pass@example.org:80/path/123?search=baz#bar');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUriParserSchemeInvalidFirstChar()
    {
        $uriParser = new UriParser();
        $uriParser->parse('1https://user:pass@example.org:80/path/123?search=baz#bar');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUriParserSchemeMissingScheme()
    {
        $uriParser = new UriParser();
        $uriParser->parse('://user:pass@example.org:80/path/123?search=baz#bar');
    }

    public function testUriParserHost()
    {
        $uriParser = new UriParser();
        $parsed = $uriParser->parse('https://user:pass@example.org:80/path/123?search=baz#bar');


//        $a = 'https://example.org:80/path';
//        $b = 'https://user:pass@example.org:80/path';
//        $c = 'https://user:pass@example.org/path';
//        $d = 'https://user:pass@example.org:80/path';


        $pattern = '~^(?P<scheme>.+?)(?=:)~';

        preg_match($pattern, 'https://user:pass@example.org:80/path/123?search=baz#bar', $matches);
        var_dump($matches);

        static::assertEquals('example.org', $parsed['host']);
    }

}
