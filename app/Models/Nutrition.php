<?php

namespace App\Models;

use Database\Factories\NutritionFactory;
use App\Enum\NutritionCategory;
use App\Enum\NutritionGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $unit
 * @property string $description
 * @property NutritionCategory $category
 * @property NutritionGroup $group
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Intake> $intakes
 * @property-read int|null $intakes_count
 * @method static \Database\Factories\NutritionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nutrition whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
