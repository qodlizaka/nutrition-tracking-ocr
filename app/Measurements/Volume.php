<?php

namespace App\Measurements;

use App\Models\Food;

class Volume extends Base
{
    public string $name = 'Volume';
    public string $unit = 'ml';
    public string $icon = 'scale';

    public function getMultiplier(): float
    {
        if ($this->food->unit !== $this->unit)
            return 0;

        return $this->value / $this->food->total_servings; # times the density of the food
    }
}
