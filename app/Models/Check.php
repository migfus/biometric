<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Check extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function attachments() {
        return $this->hasMany(Attachment::class, 'check_id');
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function verified_user() {
        return $this->belongsTo(User::class, 'verified_user_id', 'id');
    }
}
