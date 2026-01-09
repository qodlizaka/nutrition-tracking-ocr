<?php

namespace App\Measurements;

use App\Models\Food;

class Weight extends Base
{
    public string $name = 'Weight';
    public string $unit = 'g';
    public string $icon = 'scale';
    public float $value = 100;

    public function getMultiplier(): float
    {
        if ($this->food->unit !== $this->unit)
            return 0;

        return $this->value / $this->food->total_servings;
    }
}
