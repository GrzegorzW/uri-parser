<?php

declare(strict_types = 1);

namespace App\Extractors;

class HostExtractor extends AbstractExtractor
{
    protected function getRegex(): string
    {
        return '~.*~';
    }

    protected function getName(): string
    {
        return 'host';
    }

    protected function trim(string $subject, string $match): string
    {
        return '';
    }

}
