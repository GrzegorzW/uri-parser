<?php

declare(strict_types = 1);

namespace App\Extractors;

class PortExtractor extends AbstractExtractor
{
    /**
     * @return string
     */
    protected function getRegex(): string
    {
        return '~(?<=:)[0-9]*$~';
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'port';
    }

    /**
     * @param string $subject
     * @param string $match
     *
     * @return string
     */
    protected function trim(string $subject, string $match): string
    {
        return str_replace(':' . $match, '', $subject);
    }
}
