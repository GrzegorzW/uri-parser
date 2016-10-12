<?php

declare(strict_types = 1);

namespace App\Extractors;

class QueryExtractor extends AbstractExtractor
{
    public function getRegex(): string
    {
        return '~(?<=\?).*~';
    }

    public function getName(): string
    {
        return 'query';
    }

    public function trim(string $subject, string $match): string
    {
        return str_replace('?' . $match, '', $subject);
    }
}
