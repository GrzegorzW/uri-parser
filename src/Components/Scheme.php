<?php

declare(strict_types = 1);

namespace App\Components;

class Scheme extends AbstractComponent
{
    public function extract(string $url): string
    {
        $allowedChars = 'a-zA-Z0-9\-\+\.';

        preg_match("~^(?P<scheme>[a-zA-Z][$allowedChars]*?(?=:))~", $url, $matches);

        return array_key_exists('scheme', $matches) ? $matches['scheme'] : '';
    }

    public function getName(): string
    {
        return 'scheme';
    }
}
