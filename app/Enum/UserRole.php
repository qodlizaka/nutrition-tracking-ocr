<?php

namespace App\Enum;

use App\Trait\EnumToArray;

enum UserRole: int
{
    use EnumToArray;

    case User = 1;
    case Admin = 2;
}
