<?php

namespace App\Models;

use App\Enum\PhysicalActivityLevel;
use Database\Factories\UserDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property float $weight
 * @property float $height
 * @property PhysicalActivityLevel $activity_level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\UserDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereActivityLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserDetail whereWeight($value)
 *
 * @mixin \Eloquent
 */
class UserDetail extends Model
{
    /** @use HasFactory<UserDetailFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function casts(): array
    {
        return [
            'weight' => 'float',
            'height' => 'float',
            'activity_level' => PhysicalActivityLevel::class,
        ];
    }

    public function isIdenticalTo(?float $weight, ?float $height, ?PhysicalActivityLevel $activity): bool
    {
        return $this->weight === $weight
            && $this->height === $height
            && $this->activity_level === $activity;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }
}
