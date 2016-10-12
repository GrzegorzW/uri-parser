<?php

declare(strict_types = 1);

namespace Tests\App\Extractors;

use App\Extractors\PassExtractor;
use Tests\App\TestCase;

class PassExtractorTest extends TestCase
{
    public function testPassExtractorGetRegex()
    {
        $extractor = new PassExtractor();
        $method = $this->getMethod($extractor, 'getRegex');

        static::assertEquals('~.*(?=@)~', $method->invoke($extractor));
    }

    public function testPassExtractorGetName()
    {
        $extractor = new PassExtractor();
        $method = static::getMethod($extractor, 'getName');

        static::assertEquals('pass', $method->invoke($extractor));
    }

    public function testPassExtractorTrim()
    {
        $extractor = new PassExtractor();
        $method = static::getMethod($extractor, 'trim');

        $args = ['pass@example.org', 'pass'];
        $result = $method->invokeArgs($extractor, $args);

        static::assertEquals('example.org', $result);
    }

}
