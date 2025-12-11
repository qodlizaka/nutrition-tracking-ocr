<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Food extends Model
{
    /** @use HasFactory<\Database\Factories\FoodFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizeable');
    }

    public function nutritions(): MorphToMany
    {
        return $this->morphToMany(Nutrition::class, 'nutrientable')
            ->withPivot(['value']);
    }
}
