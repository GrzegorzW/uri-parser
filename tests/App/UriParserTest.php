<?php

declare(strict_types = 1);

namespace Tests\App;

use App\Extractors\FragmentExtractor;
use App\UriParser;

class UriParserTest extends TestCase
{

    public function urlProvider()
    {
        return [
            ['http://example.com', [
                'scheme' =>'http',
                'host' => 'example.com'
            ]],
            ['http://example.com:80', [
                'scheme' =>'http',
                'host' => 'example.com',
                'port' => '80'
            ]],
            ['http://example.com:80/path/to', [
                'scheme' =>'http',
                'host' => 'example.com',
                'port' => '80',
                'path' => '/path/to'
            ]],
            ['http://example.com:80/path/to?a=1&b=2', [
                'scheme' =>'http',
                'host' => 'example.com',
                'port' => '80',
                'path' => '/path/to',
                'query' => 'a=1&b=2'
            ]],
            ['http://example.com:80/path/to?a=1&b=2#bar', [
                'scheme' =>'http',
                'host' => 'example.com',
                'port' => '80',
                'path' => '/path/to',
                'query' => 'a=1&b=2',
                'fragment' => 'bar'
            ]],
            ['http://awesome_user@example.com:80/path/to?a=1&b=2#bar', [
                'user' => 'awesome_user',
                'scheme' =>'http',
                'host' => 'example.com',
                'port' => '80',
                'path' => '/path/to',
                'query' => 'a=1&b=2',
                'fragment' => 'bar'
            ]],
            ['http://awesome_user:secret@example.com:80/path/to?a=1&b=2#bar', [
                'user' => 'awesome_user',
                'pass' => 'secret',
                'scheme' =>'http',
                'host' => 'example.com',
                'port' => '80',
                'path' => '/path/to',
                'query' => 'a=1&b=2',
                'fragment' => 'bar'
            ]],
            ['http://localhost', [
                'scheme' =>'http',
                'host' => 'localhost'
            ]],
            ['http://127.0.0.1', [
                'scheme' =>'http',
                'host' => '127.0.0.1'
            ]],
            ['http://127.0.0.1:8888', [
                'scheme' =>'http',
                'host' => '127.0.0.1',
                'port' => '8888'
            ]],
            ['ftp://127.0.0.1:8888', [
                'scheme' =>'ftp',
                'host' => '127.0.0.1',
                'port' => '8888'
            ]]
        ];
    }

    /**
     * @dataProvider urlProvider
     * @param $url
     * @param $expectedComponents
     */
    public function testParse($url, $expectedComponents)
    {
        $parser = new UriParser();

        $parsedComponents = $parser->parse($url);

        static::assertEquals($expectedComponents, $parsedComponents);
    }

    public function testParseBasicOperations()
    {
        $parser = new UriParser();

        $fragmentExtractor = $this->getMockBuilder(FragmentExtractor::class)
            ->setMethods(['setSuccessor', 'process'])
            ->getMock();

        $fragmentExtractor->expects(static::exactly(7))
            ->method('setSuccessor')
            ->withConsecutive(
                [static::getPropertyValue($parser, 'queryExtractor')],
                [static::getPropertyValue($parser, 'schemeExtractor')],
                [static::getPropertyValue($parser, 'pathExtractor')],
                [static::getPropertyValue($parser, 'portExtractor')],
                [static::getPropertyValue($parser, 'userExtractor')],
                [static::getPropertyValue($parser, 'passExtractor')],
                [static::getPropertyValue($parser, 'hostExtractor')]
            );

        $fragmentExtractor->expects(static::once())
            ->method('process');

        static::setPropertyValue($parser, 'fragmentExtractor', $fragmentExtractor);

        $components = $parser->parse('https://user:pass@example.org:80/path/123?search=baz#bar');
        static::assertEmpty($components);
    }

}
