<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['full_name', 'college_id', 'office_id'])]
class Employee extends Model
{

    protected $keyType = 'string';

    public $incrementing = false;

    public function office(): BelongsTo {
        return $this->belongsTo(Office::class);
    }

    public function college(): BelongsTo {
        return $this->belongsTo(College::class);
    }

    public function checks(): HasMany {
        return $this->hasMany(Check::class, 'employee_id', 'id');
    }

}
