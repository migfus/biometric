<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    protected $guarded =[];

    public function attachments() {
        return $this->hasMany(Attachment::class, 'check_id');
    }
}
