<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Intake extends Model
{
    /** @use HasFactory<\Database\Factories\IntakeFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function nutritions(): BelongsToMany
    {
        return $this->belongsToMany(related:Nutrition::class)
            ->withPivot(['value']);
    }
}
