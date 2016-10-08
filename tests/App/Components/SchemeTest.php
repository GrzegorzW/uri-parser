<?php

declare(strict_types = 1);

namespace Tests\App\Components;

use App\Components\Scheme;
use Tests\App\TestCase;

class SchemeTest extends TestCase
{

    /**
     * @dataProvider validUrlProvider
     */
    public function testSchemeSetValue(string $url, string $expectedScheme)
    {
        $scheme = new Scheme();
        $extractedValue = $scheme->extract($url);

        static::assertEquals($expectedScheme, $extractedValue);
    }

    public function testSchemeName()
    {
        $scheme = new Scheme();

        static::assertEquals('scheme', $scheme->getName());
    }

    public function testSchemeValidateEmptyScheme()
    {
        $scheme = new Scheme();
        $extractedValue = $scheme->extract('');

        static::assertEquals('', $extractedValue);
    }

    public function testSchemeValidateInvalidSchemeChars()
    {
        $scheme = new Scheme();
        $extractedValue = $scheme->extract('ht tps://user:pass@example.org:80/path/123?search=baz#bar');

        static::assertEquals('', $extractedValue);
    }

    public function testSchemeValidateFirstCharNotLetter()
    {
        $scheme = new Scheme();
        $extractedValue = $scheme->extract('1https://user:pass@example.org:80/path/123?search=baz#bar');

        static::assertEquals('', $extractedValue);
    }

}
