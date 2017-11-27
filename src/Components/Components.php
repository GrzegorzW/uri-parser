<?php

declare(strict_types = 1);

namespace App\Components;


class Components implements ComponentsInterface
{
    /**
     * @var string[]
     */
    private $components = [];

    /**
     * @param string $name
     * @param string $value
     */
    public function addComponent(string $name, string $value): void
    {
        $this->components[$name] = $value;
    }

    /**
     * @return string[]
     */
    public function getComponents(): array
    {
        return $this->components;
    }
}
