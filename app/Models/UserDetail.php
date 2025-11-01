<?php

namespace App\Models;

use App\Enum\PhysicalActivityLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    /** @use HasFactory<\Database\Factories\UserDetailFactory> */
    use HasFactory;

    public function casts(): array
    {
        return [
            'weight' => 'integer',
            'height' => 'integer',
            'activity_level' => PhysicalActivityLevel::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(related:User::class);
    }
}
