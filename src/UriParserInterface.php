<?php

declare(strict_types = 1);

namespace App;

/**
 * Interface UriParserInterface
 * @package App
 */
interface UriParserInterface
{
    /**
     * @param string $url
     * @return array
     */
    public function parse(string $url): array;
}
