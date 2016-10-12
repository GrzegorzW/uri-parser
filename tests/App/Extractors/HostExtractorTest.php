<?php

declare(strict_types = 1);

namespace Tests\App\Extractors;

use App\Extractors\HostExtractor;
use Tests\App\TestCase;

class HostExtractorTest extends TestCase
{
    public function testHostExtractorGetRegex()
    {
        $extractor = new HostExtractor();
        $method = static::getMethod($extractor, 'getRegex');

        static::assertEquals('~.*~', $method->invoke($extractor));
    }

    public function testHostExtractorGetName()
    {
        $extractor = new HostExtractor();
        $method = static::getMethod($extractor, 'getName');

        static::assertEquals('host', $method->invoke($extractor));
    }

    public function testHostExtractorTrim()
    {
        $extractor = new HostExtractor();
        $method = static::getMethod($extractor, 'trim');

        $args = ['any', 'any'];
        $result = $method->invokeArgs($extractor, $args);

        static::assertEquals('', $result);
    }

}
