<?php

namespace App\Models;

use App\Enum\NutritionCategory;
use App\Enum\NutritionGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nutrition extends Model
{
    /** @use HasFactory<\Database\Factories\NutritionFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function casts(): array
    {
        return [
            'category' => NutritionCategory::class,
            'group' => NutritionGroup::class,
        ];
    }
}
