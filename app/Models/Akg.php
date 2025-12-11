<?php

namespace App\Models;

use Database\Factories\AkgFactory;
use App\Enum\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
