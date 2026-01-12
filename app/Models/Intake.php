<?php

namespace App\Models;

use Database\Factories\IntakeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Nutrition> $nutritions
 * @property-read int|null $nutritions_count
 * @method static \Database\Factories\IntakeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intake newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intake newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intake query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intake whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intake whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intake whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intake whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Intake whereUserId($value)
 * @method static Builder<static>|Intake today()
 * @mixin \Eloquent
 */
class Intake extends Model
{
    /** @use HasFactory<IntakeFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function nutritions(): MorphToMany
    {
        return $this->morphToMany(Nutrition::class, 'nutrientable')
            ->withPivot(['value']);
    }

    public function scopeToday(Builder $query): void
    {
        $query->whereDate('created_at', today());
    }
}
