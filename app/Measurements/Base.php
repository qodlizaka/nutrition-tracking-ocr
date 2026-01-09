<?php

namespace App\Measurements;

use App\Interfaces\Measurement;
use App\Models\Food;

abstract class Base implements Measurement
{
    public string $name;
    public string $unit;
    public string $icon;
    public float $value;

    /**
     * Create a new class instance.
     */
    public function __construct(
        public Food $food,
    )
    {
    }

    public function getName(): string
    {
        return __($this->name);
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    abstract public function getMultiplier(): float;
}
