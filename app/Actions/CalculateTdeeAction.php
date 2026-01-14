<?php

namespace App\Actions;

use App\Models\User;

class CalculateTdeeAction
{
    /**
     * Create a new class instance.
     */
    public function __invoke(User $user, float $bmr): float
    {
        return $user->detail->activity_level->getMultiplier() * $bmr;
    }
}
