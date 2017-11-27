<?php

declare(strict_types = 1);

namespace Tests\App\Extractors;

use App\Extractors\UserExtractor;
use Tests\App\TestCase;

class UserExtractorTest extends TestCase
{
    public function testUserExtractorGetRegex()
    {
        $extractor = new UserExtractor();
        $method = static::getMethod($extractor, 'getRegex');

        static::assertEquals('~(?:.*:)?.*(?=:)|.*(?=@)~', $method->invoke($extractor));
    }

    public function testUserExtractorGetName()
    {
        $extractor = new UserExtractor();
        $method = static::getMethod($extractor, 'getName');

        static::assertEquals('user', $method->invoke($extractor));
    }

    public function testUserExtractorTrimWithPass()
    {
        $extractor = new UserExtractor();
        $method = static::getMethod($extractor, 'trim');

        $args = ['user:pass@example.org', 'user'];
        $result = $method->invokeArgs($extractor, $args);

        static::assertEquals('pass@example.org', $result);
    }

    public function testUserExtractorTrimWithoutPass()
    {
        $extractor = new UserExtractor();
        $method = static::getMethod($extractor, 'trim');

        $args = ['user@example.org', 'user'];
        $result = $method->invokeArgs($extractor, $args);

        static::assertEquals('example.org', $result);
    }
}
