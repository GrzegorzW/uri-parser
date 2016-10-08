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
}
