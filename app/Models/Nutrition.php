<?php

namespace App\Models;

use Database\Factories\NutritionFactory;
use App\Enum\NutritionCategory;
use App\Enum\NutritionGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Nutrition extends Model
{
    /** @use HasFactory<NutritionFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function casts(): array
    {
        return [
            'category' => NutritionCategory::class,
            'group' => NutritionGroup::class,
        ];
    }

    public function intakes(): BelongsToMany
    {
        return $this->belongsToMany(related:Intake::class)
            ->withPivot(['value']);
    }
}
