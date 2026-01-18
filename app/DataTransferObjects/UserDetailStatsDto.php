<?php

namespace App\DataTransferObjects;

class UserDetailStatsDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $date,
        public int $bmr,
        public int $tdee
    )
    {}
}
