<?php

declare(strict_types = 1);

namespace Tests\App;

use ReflectionClass;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected static function getUriMethod($instance, $name)
    {
        $class = new ReflectionClass($instance);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected static function setPropertyValue($instance, $name, $value)
    {
        $class = new ReflectionClass($instance);
        $method = $class->getProperty($name);
        $method->setAccessible(true);
        $method->setValue($instance, $value);
    }

    public function validUrlProvider()
    {
        return [
            ['https://user:pass@example.org:80/path/123?search=baz#bar', 'https'],
            ['https://@example.org:80/path/123?search=baz#bar', 'https'],
            ['http://@example.org/path/123?search=baz#bar', 'http'],
            ['http://@example.org/path/123?search=baz', 'http'],
            ['http://@example.org/path/123', 'http'],
            ['foo://example.com:8042/over/there?name=ferret#nose', 'foo']
        ];
    }
}
