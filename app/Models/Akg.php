<?php

namespace App\Models;

use App\Enum\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
