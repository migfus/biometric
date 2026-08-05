<?php

namespace Database\Seeders;

use App\Models\BiometricDeviceStatus;
use Illuminate\Database\Seeder;

class BiometricDeviceStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Active'],
            ['name' => 'Inactive'],
            ['name' => 'Under Maintenance'],
            ['name' => 'Disabled'],
        ];

        foreach ($statuses as $status) {
            BiometricDeviceStatus::create($status);
        }
    }
}
