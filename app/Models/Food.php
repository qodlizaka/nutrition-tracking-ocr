<?php

namespace App\Models;

use App\Enum\FoodStatus;
use Database\Factories\FoodFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property int $total_servings
 * @property string $unit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Nutrition> $nutritions
 * @property-read int|null $nutritions_count
 * @method static \Database\Factories\FoodFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Food newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Food newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Food query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Food whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Food whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Food whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Food whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Food whereTotalServings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Food whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Food whereUpdatedAt($value)
 * @property FoodStatus $status
 * @method static Builder<static>|Food ofStatus(\App\Enum\FoodStatus $status)
 * @method static Builder<static>|Food whereStatus($value)
 * @mixin \Eloquent
 */
class Food extends Model
{
    /** @use HasFactory<FoodFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public static function booted(): void
    {
        static::deleted(function (Food $food) {
            Storage::disk('public')->delete($food->image);
        });
    }

    public function casts()
    {
        return [
            'status' => FoodStatus::class,
            'total_servings' => 'float',
        ];
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizeable');
    }

    public function nutritions(): MorphToMany
    {
        return $this->morphToMany(Nutrition::class, 'nutrientable')
            ->withPivot(['value', 'percentage']);
    }

    public function scopeOfStatus(Builder $query, FoodStatus $status): void
    {
        $query->where('status', $status);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }
}
