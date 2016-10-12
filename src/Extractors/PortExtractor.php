<?php

declare(strict_types = 1);

namespace App\Extractors;

class PortExtractor extends AbstractExtractor
{
    protected function getRegex(): string
    {
        return '~(?<=:)[0-9]*$~';
    }

    protected function getName(): string
    {
        return 'port';
    }

    protected function trim(string $subject, string $match): string
    {
        return str_replace(':' . $match, '', $subject);
    }

}
