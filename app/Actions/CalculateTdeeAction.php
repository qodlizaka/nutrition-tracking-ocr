<?php

namespace App\Actions;

use App\Models\UserDetail;

class CalculateTdeeAction
{
    /**
     * Calculate TDEE safely. Returns 0 if detail is missing.
     */
    public function __invoke(?UserDetail $detail, float $bmr): float
    {
        if (! $detail || ! $detail->activity_level) {
            return 0.0;
        }

        return $detail->activity_level->getMultiplier() * $bmr;
    }
}
