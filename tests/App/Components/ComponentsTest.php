<?php

declare(strict_types = 1);

namespace Tests\App\Components;

use App\Components\Components;
use Tests\App\TestCase;

class ComponentsTest extends TestCase
{
    public function testComponents()
    {
        $components = new Components();

        static::assertEmpty($components->getComponents());

        $components->addComponent('foo', 'bar');
        static::assertCount(1, $components->getComponents());
        static::assertEquals('bar', $components->getComponents()['foo']);

        $components->addComponent('buzz', 'biz');
        static::assertCount(2, $components->getComponents());
        static::assertEquals('bar', $components->getComponents()['foo']);
        static::assertEquals('biz', $components->getComponents()['buzz']);
    }
}
