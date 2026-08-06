<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AreaSeeder::class,
            UserSeeder::class,
            EmploymentTypeSeeder::class,
            CheckStatusSeeder::class,
            ReportTypeSeeder::class,

            BiometricDeviceStatusSeeder::class,
            BiometricDeviceSeeder::class,
            EmployeesSeeder::class,

            ReportSeeder::class,
        ]);
    }
}
