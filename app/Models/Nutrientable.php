<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Nutrientable extends MorphPivot
{
    protected $table = 'nutrientables';
    public $timestamps = true;
    const UPDATED_AT = null;
    public function nutrition(): BelongsTo
    {
        return $this->belongsTo(Nutrition::class);
    }

    public function nutrientable(): MorphTo
    {
        return $this->morphTo();
    }
}
