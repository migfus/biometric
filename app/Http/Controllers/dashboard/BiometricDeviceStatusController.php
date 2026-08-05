<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\BiometricDeviceStatus;
use Inertia\Inertia;
use Inertia\Response;

class BiometricDeviceStatusController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('dashboard/biometric-device-statuses/index', [
            'page_title' => 'Biometric Device Statuses',
            'navigation' => 'sidebar',

            'biometric_device_statuses' => BiometricDeviceStatus::query()
                ->withCount('biometricDevices')
                ->orderBy('name', 'ASC')
                ->paginate(20),
        ]);
    }
}
