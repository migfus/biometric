<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
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
