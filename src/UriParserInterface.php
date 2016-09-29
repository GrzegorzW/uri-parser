<?php

declare(strict_types = 1);

namespace App;

use Psr\Http\Message\UriInterface;

/**
 * Interface UriParserInterface
 * @package App
 */
interface UriParserInterface
{
    /**
     * @param string $url
     * @return UriInterface
     */
    public function parse(string $url): UriInterface;
}
