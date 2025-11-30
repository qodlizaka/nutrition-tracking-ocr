<?php

namespace App\Enum;

use App\Trait\EnumToArray;

enum Gender: int
{
    use EnumToArray;

    case Male = 1;
    case Female = 2;
}
