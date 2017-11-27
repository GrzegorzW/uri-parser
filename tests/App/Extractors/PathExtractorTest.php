<?php

declare(strict_types = 1);

namespace Tests\App\Extractors;

use App\Extractors\PathExtractor;
use Tests\App\TestCase;

class PathExtractorTest extends TestCase
{
    public function testPathExtractorGetRegex()
    {
        $extractor = new PathExtractor();
        $method = static::getMethod($extractor, 'getRegex');

        static::assertEquals('~/.*~', $method->invoke($extractor));
    }

    public function testPathExtractorGetName()
    {
        $extractor = new PathExtractor();
        $method = static::getMethod($extractor, 'getName');

        static::assertEquals('path', $method->invoke($extractor));
    }

    public function testPathExtractorTrim()
    {
        $extractor = new PathExtractor();
        $method = static::getMethod($extractor, 'trim');

        $args = ['https://example.org:80/path/123', '/path/123'];
        $result = $method->invokeArgs($extractor, $args);

        static::assertEquals('https://example.org:80', $result);
    }
}
