<?php

namespace App\Measurements;

use App\Models\Food;
use Rector\Php70\Rector\StmtsAwareInterface\IfIssetToCoalescingRector;

class Volume extends Base
{
    public string $name = 'Volume';

    public string $unit = 'ml';

    public string $icon = 'cup-soda';

    public function getMultiplier(): float
    {
        if ($this->food->unit !== $this->unit) {
            return 0;
        }

        return $this->value * ($this->baseMultiplier / $this->food->total_servings);
    }

    public function setBaseMultiplier(float $baseMultiplier): static
    {
        $this->baseMultiplier = $baseMultiplier;

        return $this;
    }
}
