<?php

declare(strict_types = 1);

namespace App\Components;


interface ComponentsInterface
{
    public function addComponent(string $name, string $value);
    public function getComponents(): array;
}
