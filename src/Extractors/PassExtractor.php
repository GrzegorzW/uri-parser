<?php

declare(strict_types = 1);

namespace App\Extractors;

class PassExtractor extends AbstractExtractor
{
    /**
     * @return string
     */
    protected function getRegex(): string
    {
        return '~.*(?=@)~';
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'pass';
    }

    /**
     * @param string $subject
     * @param string $match
     *
     * @return string
     */
    protected function trim(string $subject, string $match): string
    {
        return str_replace($match . '@', '', $subject);
    }
}
