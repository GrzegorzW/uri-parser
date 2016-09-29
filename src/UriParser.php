<?php

declare(strict_types = 1);

namespace App;

use Psr\Http\Message\UriInterface;

/**
 * Class UriParser
 * @package App
 */
class UriParser implements UriParserInterface
{
    /**
     * @param string $url
     * @return UriInterface
     */
    public function parse(string $url): UriInterface
    {
        return new Uri($url);
    }
}
