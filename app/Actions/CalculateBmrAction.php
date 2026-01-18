<?php

namespace App\Actions;

use App\Models\User;
use App\Models\UserDetail;

class CalculateBmrAction
{
    /**
     * Create a new class instance.
     */
    public function __invoke(User $user, UserDetail $detail): float
    {
        $age = $user->date_of_birth->age;
        $weight = $detail->weight;
        $height = $detail->height;

        if ($user->gender->isMale())
            return 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);

        return 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    }
}
