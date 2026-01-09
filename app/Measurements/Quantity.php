<?php

namespace App\Measurements;

use App\Interfaces\Measurement;

class Quantity extends Base
{
    public string $name = 'Quantity';
    public string $unit = 'serving';
    public string $icon = 'hamburger';
    public float $value = 1;

    public function getMultiplier(): float
    {
        return $this->value;
    }
}
