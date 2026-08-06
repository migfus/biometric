<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['employee_id', 'biometric_device_id', 'report_type_id', 'check_status_id', 'browser_id', 'ip_address', 'os', 'description', 'action_taken', 'rephrase_count'])]
class Report extends Model
{
    use HasFactory;

    public function reportType(): BelongsTo {
        return $this->belongsTo(ReportType::class);
    }

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class);
    }

    public function checkStatus(): BelongsTo {
        return $this->belongsTo(CheckStatus::class);
    }

    public function biometricDevice(): BelongsTo {
        return $this->belongsTo(BiometricDevice::class);
    }
}
