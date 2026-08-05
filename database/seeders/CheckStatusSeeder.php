<?php

namespace Database\Seeders;

use App\Models\CheckStatus;
use Illuminate\Database\Seeder;

class CheckStatusSeeder extends Seeder
{
    public function run(): void
    {
        $checkStatuses = [
            [
                'name' => 'Time In',
                'icon' => 'mdi:arrow-right-bold',
            ],
            [
                'name' => 'Time Out',
                'icon' => 'mdi:arrow-left-bold',
            ],
            [
                'name' => 'Time-In & Time-Out',
                'icon' => 'mdi:arrow-left-right-bold',
            ],
            [
                'name' => 'N/A',
                'icon' => 'material-symbols:circle-outline',
            ],
        ];

        foreach ($checkStatuses as $status) {
            CheckStatus::create($status);
        }
    }
}
