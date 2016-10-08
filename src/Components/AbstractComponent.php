<?php

declare(strict_types = 1);

namespace App\Components;


abstract class AbstractComponent
{
    /**
     * Extracts component value.
     *
     * @param string $url
     * @return string
     */
    abstract public function extract(string $url): string ;

    /**
     * Returns component name.
     *
     * @return string
     */
    abstract public function getName(): string ;

    public function getSubDelims(): string
    {
        return '!$&\'\(\)\*\+,;=';
    }

    public function getGenDelims(): string
    {
        return '\:\/\?\#\[\]\@';
    }

    public function getReserved(): string
    {
        return $this->getGenDelims() . $this->getSubDelims();
    }

    public function getUnreserved(): string
    {
        return '[a-zA-Z0-9\-\.\_\~]';
    }

    public function getPctEncoded()
    {
        return '%[a-fA-F0-9]{2}';
    }

    public function getPchar()
    {
        return $this->getUnreserved() . $this->getPctEncoded() . $this->getSubDelims() . '\:\@';
    }

}