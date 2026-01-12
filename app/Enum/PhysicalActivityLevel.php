<?php

namespace App\Enum;

use App\Trait\EnumToArray;

enum PhysicalActivityLevel: int
{
    use EnumToArray;

    case Sedentary = 1;
    case LightlyActive = 2;
    case ModeratelyActive = 3;
    case VeryActive = 4;
    case ExtraActive = 5;

    public function getLabel(): string
    {
        return match ($this) {
            self::Sedentary => __('Sedentary'),
            self::LightlyActive => __('Lightly Active'),
            self::ModeratelyActive => __('Moderately Active'),
            self::VeryActive => __('Very Active'),
            self::ExtraActive => __('Extra Active'),
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Sedentary => __('Little to no exercise, desk job'),
            self::LightlyActive => __('Light exercise/sports 1-3 days a week'),
            self::ModeratelyActive => __('Moderate exercise/sports 3-5 days a week'),
            self::VeryActive => __('Hard exercise/sports 6-7 days a week'),
            self::ExtraActive => __('Very hard exercise, physical job, or training twice a day'),
        };
    }

    public function getMultiplier(): float
    {
        return match ($this) {
            self::Sedentary => 1.2,
            self::LightlyActive => 1.375,
            self::ModeratelyActive => 1.55,
            self::VeryActive => 1.725,
            self::ExtraActive => 1.9,
        };
    }
}
