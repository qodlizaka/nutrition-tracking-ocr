<?php

namespace App\Models;

use Database\Factories\AlgFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property string $name
 * @property int $energy
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Nutrition> $nutritions
 * @property-read int|null $nutritions_count
 * @method static \Database\Factories\AlgFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alg newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alg newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alg query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alg whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alg whereEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alg whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alg whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alg whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Alg extends Model
{
    /** @use HasFactory<AlgFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function nutritions(): MorphToMany
    {
        return $this->morphToMany(Nutrition::class, 'nutrientable')
            ->withPivot(['value']);
    }
}
