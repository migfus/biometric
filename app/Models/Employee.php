<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id', 'full_name', 'office_id'])]
class Employee extends Model
{

    protected $keyType = 'string';

    public $incrementing = false;

    public function office(): BelongsTo {
        return $this->belongsTo(Office::class);
    }

    public function reports(): HasMany {
        return $this->hasMany(Report::class);
    }
}
