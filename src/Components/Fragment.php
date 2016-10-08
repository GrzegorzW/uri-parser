<?php

declare(strict_types = 1);

namespace App\Components;

class Fragment extends AbstractComponent
{
    public function extract(string $url): string
    {
        $allowedChars = $this->getPchar() . '\/\?)';

        preg_match("~(?P<fragment>(?<=#)[$allowedChars]*)$~", $url, $matches);

        return array_key_exists('fragment', $matches) ? $matches['fragment'] : '';
    }

    public function getName(): string
    {
        return 'fragment';
    }

}
