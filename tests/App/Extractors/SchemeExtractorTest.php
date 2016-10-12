<?php

declare(strict_types = 1);

namespace Tests\App\Extractors;

use App\Extractors\SchemeExtractor;
use Tests\App\TestCase;

class SchemeExtractorTest extends TestCase
{
    public function testSchemeExtractorGetRegex()
    {
        $extractor = new SchemeExtractor();
        $method = static::getMethod($extractor, 'getRegex');

        static::assertEquals('~[a-zA-Z].*?(?=://)~', $method->invoke($extractor));
    }

    public function testSchemeExtractorGetName()
    {
        $extractor = new SchemeExtractor();
        $method = static::getMethod($extractor, 'getName');

        static::assertEquals('scheme', $method->invoke($extractor));
    }

    public function testSchemeExtractorTrim()
    {
        $extractor = new SchemeExtractor();
        $method = static::getMethod($extractor, 'trim');

        $args = ['https://example.org:80', 'https'];
        $result = $method->invokeArgs($extractor, $args);

        static::assertEquals('example.org:80', $result);
    }

}
