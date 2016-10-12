<?php

declare(strict_types = 1);

namespace App\Extractors;

class SchemeExtractor extends AbstractExtractor
{
    public function getRegex(): string
    {
        return '~[a-zA-Z].*?(?=://)~';
    }

    public function getName(): string
    {
        return 'scheme';
    }

    public function trim(string $subject, string $match): string
    {
        return str_replace($match . '://', '', $subject);

    }
}
