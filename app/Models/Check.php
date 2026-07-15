<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['browser_id', 'ip_address', 'ip_location', 'os', 'employee_id', 'verified_user_id', 'verified_at', 'check_in', 'work_description', 'rephrase_count'])]

class Check extends Model
{
    use SoftDeletes;

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
