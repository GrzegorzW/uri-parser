<?php

declare(strict_types = 1);

namespace Tests\App\Extractors;

use App\Extractors\QueryExtractor;
use Tests\App\TestCase;

class QueryExtractorTest extends TestCase
{
    public function testQueryExtractorGetRegex()
    {
        $extractor = new QueryExtractor();
        $method = static::getMethod($extractor, 'getRegex');

        static::assertEquals('~(?<=\?).*~', $method->invoke($extractor));
    }

    public function testFragmentExtractorGetName()
    {
        $extractor = new QueryExtractor();
        $method = static::getMethod($extractor, 'getName');

        static::assertEquals('query', $method->invoke($extractor));
    }

    public function testFragmentExtractorTrim()
    {
        $extractor = new QueryExtractor();
        $method = static::getMethod($extractor, 'trim');

        $args = ['https://example.org:80/path/123?search=baz', 'search=baz'];
        $result = $method->invokeArgs($extractor, $args);

        static::assertEquals('https://example.org:80/path/123', $result);
    }
}
