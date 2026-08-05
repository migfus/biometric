<?php

namespace Database\Seeders;

use App\Models\EmploymentType;
use Illuminate\Database\Seeder;

class EmploymentTypeSeeder extends Seeder
{
    public function run(): void
    {

        $employmentTypes = [
            ['name' => 'Casual'],
            ['name' => 'Contract of Service'],
            ['name' => 'Contractual'],
            ['name' => 'CoTerminous'],
            ['name' => 'Elective'],
            ['name' => 'Job Order'],
            ['name' => 'Part-Time'],
            ['name' => 'Permanent'],
            ['name' => 'Provisional'],
            ['name' => 'Seasonal'],
            ['name' => 'Substitute'],
            ['name' => 'Temporary'],
        ];

        foreach ($employmentTypes as $type) {
            EmploymentType::create($type);
        }
    }
}
