<?php

namespace App\Models;

use Database\Factories\AkgFactory;
use App\Enum\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property string $name
 * @property int $min_age
 * @property int $max_age
 * @property Gender|null $gender
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Nutrition> $nutritions
 * @property-read int|null $nutritions_count
 * @method static \Database\Factories\AkgFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Akg newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Akg newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Akg query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Akg whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Akg whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Akg whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Akg whereMaxAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Akg whereMinAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Akg whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Akg whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Akg extends Model
{
    /** @use HasFactory<AkgFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function casts(): array
    {
        return [
            'gender' => Gender::class,
        ];
    }

    public function nutritions(): MorphToMany
    {
        return $this->morphToMany(Nutrition::class, 'nutrientable')
            ->withPivot(['value', 'percentage']);
    }
}
