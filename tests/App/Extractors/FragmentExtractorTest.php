<?php

declare(strict_types = 1);

namespace Tests\App\Extractors;

use App\Extractors\FragmentExtractor;
use Tests\App\TestCase;

class FragmentExtractorTest extends TestCase
{
    public function testFragmentExtractorGetRegex()
    {
        $extractor = new FragmentExtractor();
        $method = static::getMethod($extractor, 'getRegex');

        static::assertEquals('~(?<=#).*~', $method->invoke($extractor));
    }

    public function testFragmentExtractorGetName()
    {
        $extractor = new FragmentExtractor();
        $method = static::getMethod($extractor, 'getName');

        static::assertEquals('fragment', $method->invoke($extractor));
    }

    public function testFragmentExtractorTrim()
    {
        $extractor = new FragmentExtractor();
        $method = static::getMethod($extractor, 'trim');

        $args = ['https://example.org:80/path/123?search=baz#bar', 'bar'];
        $result = $method->invokeArgs($extractor, $args);

        static::assertEquals('https://example.org:80/path/123?search=baz', $result);
    }
}
