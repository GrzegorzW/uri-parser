<?php

declare(strict_types = 1);

namespace App\Components;

class Fragment extends AbstractComponent
{
    private $fragmentRegex = '(?P<fragment>(?<=#).*)$';

    public function extract(string $url): string
    {
        preg_match('~' . $this->fragmentRegex . '~', $url, $matches);

        return array_key_exists('fragment', $matches) ? $matches['fragment'] : '';
    }

    public function getName(): string
    {
        return 'fragment';
    }

}
