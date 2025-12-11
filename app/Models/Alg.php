<?php

namespace App\Models;

use Database\Factories\AlgFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
