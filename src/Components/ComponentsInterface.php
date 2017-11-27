<?php

declare(strict_types = 1);

namespace App\Components;

interface ComponentsInterface
{
    /**
     * @param string $name
     * @param string $value
     */
    public function addComponent(string $name, string $value): void;

    /**
     * @return array
     */
    public function getComponents(): array;
}
