<?php

namespace App\Models;

use Database\Factories\UserDetailFactory;
use App\Enum\PhysicalActivityLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(related:User::class);
    }
}
