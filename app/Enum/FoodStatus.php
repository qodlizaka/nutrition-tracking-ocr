<?php

namespace App\Enum;

use App\Trait\EnumToArray;

enum FoodStatus: int
{
    use EnumToArray;

    case Inactive = 1;
    case Pending = 2;
    case Active = 3;

    public function getLabel(): string
    {
        return match($this) {
            self::Inactive => 'Inactive',
            self::Pending => 'Pending',
            self::Active => 'Active',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::Inactive => __('Deleted after 24 hours of creation.'),
            self::Pending => __('Waiting for Admin review.'),
            self::Active => __('Available for other User.'),
        };
    }
}
