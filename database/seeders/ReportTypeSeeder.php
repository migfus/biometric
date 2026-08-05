<?php

namespace Database\Seeders;

use App\Models\ReportType;
use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    public function run(): void
    {
        $report_types = [
            [
                'name' => 'Fingerprint not recognized',
                'icon' => 'material-symbols:fingerprint',
            ],
            [
                'name' => 'Time-In not Recorded',
                'icon' => 'material-symbols:login',
            ],
            [
                'name' => 'Time-Out not Recorded',
                'icon' => 'material-symbols:logout',
            ],
            [
                'name' => 'Incorrect date or time',
                'icon' => 'mdi:clock-alert',
            ],
            [
                'name' => 'Machine offline',
                'icon' => 'material-symbols:light-off',
            ],
            [
                'name' => 'Machine frozen or unresponsive',
                'icon' => 'mdi:ban',
            ],
            [
                'name' => 'Power Interruption (black out)',
                'icon' => 'ix:voltage',
            ],
        ];

        foreach ($report_types as $report_type) {
            ReportType::create($report_type);
        }
    }
}
