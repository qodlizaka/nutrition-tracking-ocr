<?php

namespace App\Measurements;

use App\Models\Food;

class Volume extends Base
{
    public string $name = 'Volume';
    public string $unit = 'ml';
    public string $icon = 'scale';
    public float $baseMultiplier = 1;

    public function getMultiplier(): float
    {
        if ($this->food->unit !== $this->unit)
            return 0;

        return ($this->value * $this->baseMultiplier) / $this->food->total_servings; # times the density of the food
    }

    public function setBaseMultiplier(float $baseMultiplier): static
    {
        $this->baseMultiplier = $baseMultiplier;

        return $this;
    }
}
