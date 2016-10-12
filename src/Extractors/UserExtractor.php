<?php

declare(strict_types = 1);

namespace App\Extractors;

class UserExtractor extends AbstractExtractor
{
    protected function getRegex(): string
    {
        return '~(?:.*:)?.*(?=:)|.*(?=@)~';
    }

    protected function getName(): string
    {
        return 'user';
    }

    protected function trim(string $subject, string $match): string
    {
        return preg_replace("~$match.~", '', $subject);
    }

}
