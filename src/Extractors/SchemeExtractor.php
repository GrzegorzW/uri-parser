<?php

declare(strict_types = 1);

namespace App\Extractors;

class SchemeExtractor extends AbstractExtractor
{
    /**
     * @return string
     */
    protected function getRegex(): string
    {
        return '~[a-zA-Z].*?(?=://)~';
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'scheme';
    }

    /**
     * @param string $subject
     * @param string $match
     *
     * @return string
     */
    protected function trim(string $subject, string $match): string
    {
        return str_replace($match . '://', '', $subject);
    }
}
