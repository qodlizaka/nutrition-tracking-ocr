<?php

namespace App\Enum;

use App\Trait\EnumToArray;

enum Gender: int
{
    use EnumToArray;

    case Male = 1;
    case Female = 2;

    public function isMale(): bool
    {
        return $this === self::Male;
    }

    public function isFemale(): bool
    {
        return $this === self::Female;
    }

}
