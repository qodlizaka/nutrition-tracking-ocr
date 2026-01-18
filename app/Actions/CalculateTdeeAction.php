<?php

namespace App\Actions;

use App\Models\User;
use App\Models\UserDetail;

class CalculateTdeeAction
{
    /**
     * Create a new class instance.
     */
    public function __invoke(UserDetail $detail, float $bmr): float
    {
        return $detail->activity_level->getMultiplier() * $bmr;
    }
}
