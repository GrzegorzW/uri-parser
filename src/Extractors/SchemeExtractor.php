<?php

declare(strict_types = 1);

namespace App\Extractors;

class SchemeExtractor extends AbstractExtractor
{
    protected function getRegex(): string
    {
        return '~[a-zA-Z].*?(?=://)~';
    }

    protected function getName(): string
    {
        return 'scheme';
    }

    protected function trim(string $subject, string $match): string
    {
        return str_replace($match . '://', '', $subject);
    }
}
