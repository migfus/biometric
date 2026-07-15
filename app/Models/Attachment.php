<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['check_id', 'file_location', 'file_size', 'preview_location'])]
class Attachment extends Model
{
    use SoftDeletes;

    public function check() {
        return $this->belongsTo(Check::class, 'check_id', 'id');
    }
}
