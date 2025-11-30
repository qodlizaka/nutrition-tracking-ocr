<?php

namespace App\Enum;

use App\Traits\EnumToArray;

enum Gender: int
{
    use EnumToArray;

    case Male = 1;
    case Female = 2;
}
