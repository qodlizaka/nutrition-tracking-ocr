<?php

namespace App\Enum;

enum NutritionGroup: int
{
    case Macro = 1;
    case Fat = 2;
    case Carbohydrate = 3;
    case Vitamin = 4;
    case Mineral = 5;
    case Other = 6;
}
