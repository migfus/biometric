<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Check extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'check_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id');
    }
}
