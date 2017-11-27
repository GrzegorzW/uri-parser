<?php

declare(strict_types = 1);

namespace Tests\App\Extractors;

use App\Extractors\PortExtractor;
use Tests\App\TestCase;

class PortExtractorTest extends TestCase
{
    public function testPortExtractorGetRegex()
    {
        $extractor = new PortExtractor();
        $method = static::getMethod($extractor, 'getRegex');

        static::assertEquals('~(?<=:)[0-9]*$~', $method->invoke($extractor));
    }

    public function testPortExtractorGetName()
    {
        $extractor = new PortExtractor();
        $method = static::getMethod($extractor, 'getName');

        static::assertEquals('port', $method->invoke($extractor));
    }

    public function testPortExtractorTrim()
    {
        $extractor = new PortExtractor();
        $method = static::getMethod($extractor, 'trim');

        $args = ['https://example.org:80', '80'];
        $result = $method->invokeArgs($extractor, $args);

        static::assertEquals('https://example.org', $result);
    }
}
