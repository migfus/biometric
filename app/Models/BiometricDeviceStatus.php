<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiometricDeviceStatus extends Model
{
    public function biometricDevices(): HasMany
    {
        return $this->hasMany(BiometricDevice::class, 'biometric_device_status_id', 'id');
    }
}
