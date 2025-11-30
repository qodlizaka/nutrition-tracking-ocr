<?php

namespace App\Enum;

use App\Traits\EnumToArray;

enum NutritionCategory: int
{
    use EnumToArray;

    case Micro = 1;
    case Macro = 2;
}
