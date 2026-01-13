<?php

namespace App\Enum;

enum AspectRatio: string
{
    case Square = '1:1';
    case Standard = '3:4';
    case StandardHorizontal = '4:3';
    case Portrait = '9:16';

    public function getMultiplier(): float
    {
        return match ($this) {
            self::Square => 1.0,
            self::Standard => 3 / 4,
            self::StandardHorizontal => 4 / 3,
            self::Portrait => 9 / 16,
        };
    }

    public function getCssClass(): string
    {
        return match ($this) {
            self::Square => 'aspect-square',
            self::Standard => 'aspect-[3/4]',
            self::StandardHorizontal => 'aspect-[4/3]',
            self::Portrait => 'aspect-[9/16]',
        };
    }

    public function label(): string
    {
        return $this->value;
    }

    public function icon(): string
    {
        return match ($this) {
            self::Square => 'square',
            self::Standard => 'ratio',
            self::StandardHorizontal => 'ratio',
            self::Portrait => 'rectangle-vertical',
        };
    }
}
