<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['attachable_id', 'attachable_type', 'file_location', 'file_size', 'preview_location'])]
class Attachment extends Model
{
    use SoftDeletes;

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
