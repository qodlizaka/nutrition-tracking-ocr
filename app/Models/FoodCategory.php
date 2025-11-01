<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    /** @use HasFactory<\Database\Factories\FoodCategoryFactory> */
    use HasFactory;

    protected $guarded = ['id'];
}
