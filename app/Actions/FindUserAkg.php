<?php

namespace App\Actions;

use App\Models\Akg;
use App\Models\User;

class FindUserAkg
{
    /**
     * Invoke the class instance.
     */
    public function __invoke(User $user): Akg
    {
        return Akg::query()
            ->where('min_age', '<=', $user->date_of_birth->age)
            ->where('max_age', '>=', $user->date_of_birth->age)
            ->where('gender', $user->gender)
            ->first();
    }
}
