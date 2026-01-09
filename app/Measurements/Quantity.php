<?php

namespace App\Measurements;

use App\Interfaces\Measurement;

class Quantity implements Measurement
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private float $value,
    )
    {
    }

    public function getName(): string
    {
        return __('Quantity');
    }

    public function getMultiplier(): float
    {
        return $this->value;
    }

    public function getUnit(): string
    {
        return __('serving');
    }

    public function getIcon(): string
    {
        return 'hamburger';
    }
}
