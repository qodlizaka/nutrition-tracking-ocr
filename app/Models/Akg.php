<?php

namespace App\Models;

use App\Enum\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Akg extends Model
{
    /** @use HasFactory<\Database\Factories\AkgFactory> */
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
