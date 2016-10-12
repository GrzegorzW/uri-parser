<?php

declare(strict_types = 1);

namespace Tests\App\Extractors;

use App\Components\Components;
use App\Extractors\AbstractExtractor;
use App\Extractors\FragmentExtractor;
use App\Extractors\HostExtractor;
use App\Extractors\QueryExtractor;
use MongoDB\Driver\Query;
use ReflectionMethod;
use Tests\App\TestCase;

class AbstractExtractorTest extends TestCase
{
    public function testSetSuccessor()
    {
        $abstractExtractor = $this->getMockForAbstractClass(AbstractExtractor::class);

        static::assertNull($abstractExtractor->getSuccessor());
        $abstractExtractor->setSuccessor(new QueryExtractor());

        static::assertInstanceOf(QueryExtractor::class, $abstractExtractor->getSuccessor());
    }

    public function testSetSuccessorOfSuccessor()
    {
        $abstractExtractor = $this->getMockForAbstractClass(AbstractExtractor::class);

        $successorOfSuccessor = new QueryExtractor();

        $successor = $this->getMockBuilder(FragmentExtractor::class)
            ->setMethods(['setSuccessor'])
            ->getMock();
        $successor->expects(static::once())
            ->method('setSuccessor')
            ->with($successorOfSuccessor);

        $abstractExtractor->setSuccessor($successor);
        $abstractExtractor->setSuccessor($successorOfSuccessor);
    }

    public function testProcess()
    {
        $abstractExtractor = $this->getMockForAbstractClass(AbstractExtractor::class);
        $abstractExtractor->expects(static::once())
            ->method('getRegex')
            ->willReturn('~[a-zA-Z].*?(?=://)~');
        $abstractExtractor->expects(static::once())
            ->method('getName')
            ->willReturn('foo');

        $components = new Components();
        $abstractExtractor->process('http://example.com', $components);

        static::assertCount(1, $components->getComponents());
        static::assertEquals('http', $components->getComponents()['foo']);
    }

    public function testProcessWithDefinedSuccessor()
    {
        $abstractExtractor = $this->getMockForAbstractClass(AbstractExtractor::class);
        $abstractExtractor->expects(static::once())
            ->method('getRegex')
            ->willReturn('~[a-zA-Z].*?(?=://)~');
        $abstractExtractor->expects(static::once())
            ->method('getName')
            ->willReturn('foo');
        $abstractExtractor->expects(static::once())
            ->method('trim')
            ->willReturn('example.com');

        $components = new Components();

        $successor = $this->getMockBuilder(HostExtractor::class)
            ->setMethods(['process'])
            ->getMock();
        $successor->expects(static::once())
            ->method('process')
            ->with('example.com', $components);

        $abstractExtractor->setSuccessor($successor);
        $abstractExtractor->process('http://example.com', $components);

        static::assertCount(1, $components->getComponents());
        static::assertEquals('http', $components->getComponents()['foo']);
    }

    public function testProcessWithoutMatch()
    {
        $abstractExtractor = $this->getMockForAbstractClass(AbstractExtractor::class);
        $abstractExtractor->expects(static::once())
            ->method('getRegex')
            ->willReturn('~!~');
        $abstractExtractor->expects(static::never())
            ->method('trim');
        $abstractExtractor->expects(static::never())
            ->method('getName');

        $components = $this->getMockBuilder(Components::class)
            ->setMethods(['addComponent'])
            ->getMock();

        $components->expects(static::never())
            ->method('addComponent');

        $abstractExtractor->process('http://example.com', $components);
    }

}
