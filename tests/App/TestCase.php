<?php

declare(strict_types = 1);

namespace Tests\App;

use App\Uri;
use ReflectionClass;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected static function getUriMethod($name)
    {
        $class = new ReflectionClass(Uri::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected static function setPropertyValue($instance, $name, $value)
    {
        $class = new ReflectionClass(Uri::class);
        $method = $class->getProperty($name);
        $method->setAccessible(true);
        $method->setValue($instance, $value);
    }
}
