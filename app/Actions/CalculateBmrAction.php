<?php

namespace App\Actions;

use App\Models\User;
use App\Models\UserDetail;

class CalculateBmrAction
{
    /**
     * Calculate BMR safely. Returns 0 if detail or DOB is missing.
     */
    public function __invoke(User $user, ?UserDetail $detail): float
    {
        if (! $detail) {
            return 0.0;
        }

        if (! $user->date_of_birth) {
            return 0.0;
        }

        $age = $user->date_of_birth->age;
        $weight = $detail->weight ?? 0;
        $height = $detail->height ?? 0;

        if ($weight <= 0 || $height <= 0) {
            return 0.0;
        }

        if ($user->gender?->isMale()) {
            return 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
        }

        return 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    }
}
