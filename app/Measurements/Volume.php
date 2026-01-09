<?php

namespace App\Measurements;

use App\Models\Food;

class Volume
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private Food $food,
        private float $value,
    )
    {
    }

    public function getName(): string
    {
        return __('Weight');
    }

    public function getMultiplier(): float
    {
        if (!$this->food->unit === 'ml')
            return 0;

        return $this->value / $this->food->total_servings;
    }

    public function getUnit(): string
    {
        return __('gram');
    }

    public function getIcon(): string
    {
        return 'scale';
    }
}
