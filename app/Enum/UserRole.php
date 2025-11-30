<?php

namespace App\Enum;

use App\Traits\EnumToArray;

enum UserRole: int
{
    use EnumToArray;

    case User = 1;
    case Admin = 2;
}
