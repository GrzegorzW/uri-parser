<?php

declare(strict_types = 1);

namespace Tests\App\Components;

use App\Components\Fragment;
use App\Components\Scheme;
use Tests\App\TestCase;

class FragmentTest extends TestCase
{
    public function fragmentProvider()
    {
        return [
            ['https://user:pass@example.org:80/path/123?search=baz#bar', 'bar'],
            ['https://user:pass@example.org:80/path/123#bar', 'bar'],
            ['https://example.org:80/path/123#bar', 'bar'],
            ['https://example.org/path/123#bar', 'bar'],
            ['https://example.org/path/123#bar', 'bar'],
        ];
    }

    /**
     * @dataProvider fragmentProvider
     */
    public function testFragmentValidUrls(string $url, string $expectedScheme)
    {
        $scheme = new Fragment();
        $extractedValue = $scheme->extract($url);

        static::assertEquals($expectedScheme, $extractedValue);
    }

    public function testFragmentName()
    {
        $scheme = new Fragment();

        static::assertEquals('fragment', $scheme->getName());
    }

    public function testFragmentValidateEmptyScheme()
    {
        $scheme = new Fragment();
        $extractedValue = $scheme->extract('#');

        static::assertEquals('', $extractedValue);
    }

    public function testFragmentValidateInvalidFragmentChars()
    {
        $scheme = new Fragment();
        $extractedValue = $scheme->extract('https://user:pass@example.org:80/path/123?search=baz#b{ar');

        static::assertEquals('', $extractedValue);
    }

}
