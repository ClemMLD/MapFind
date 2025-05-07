<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $guarded = [];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
