<?php

namespace App\Models;

use Database\Factories\IntakeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
}
