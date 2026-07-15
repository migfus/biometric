<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name'])]
class College extends Model
{
    public function employees() : HasMany {
        return $this->hasMany(Employee::class);
    }
}
