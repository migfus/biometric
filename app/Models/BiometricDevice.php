<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiometricDevice extends Model
{
    public function area() : BelongsTo {
        return $this->belongsTo(Area::class);
    }
}
