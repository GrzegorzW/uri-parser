<?php

declare(strict_types = 1);

namespace App\Extractors;

class PathExtractor extends AbstractExtractor
{
    protected function getRegex(): string
    {
        return '~/.*~';
    }

    protected function getName(): string
    {
        return 'path';
    }

    protected function trim(string $subject, string $match): string
    {
        return str_replace($match, '', $subject);
    }

}
