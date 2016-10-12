<?php

declare(strict_types = 1);

namespace App\Components;


class Components implements ComponentsInterface
{
    private $components = [];

    public function addComponent(string $name, string $value)
    {
        $this->components[$name] = $value;
    }

    public function getComponents(): array
    {
        return $this->components;
    }
}
