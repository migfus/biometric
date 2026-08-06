<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void {
        $data = [
            ['name' => 'Admin Annex'],
            ['name' => 'Main Admin'],
            ['name' => 'College of Agriculture'],
            ['name' => 'College of Arts and Sciences'],
            ['name' => 'College of Business and Management'],
            ['name' => 'College of Education'],
            ['name' => 'College of Engineering'],
            ['name' => 'College of Forestry and Natural Resources'],
            ['name' => 'College of Human Ecology'],
            ['name' => 'College of Information and Communication Technology'],
            ['name' => 'College of Nursing'],
            ['name' => 'College of Veterinary Medicine'],
            ['name' => 'Office of General Services'],
            ['name' => 'School of Law'],
            ['name' => 'University Homestay'],
            ['name' => 'CEBREM'],
            ['name' => 'Extension'],
            ['name' => 'Office of Security Services'],
            ['name' => 'PRIO'],
            ['name' => 'DTO'],
            ['name' => 'Hospital'],
            ['name' => 'CMU Press'],
            ['name' => 'CMULHS'],
            ['name' => 'Library'],
            ['name' => 'OASP'],
            ['name' => 'ODT'],
            ['name' => 'OSA'],
            ['name' => 'UCC'],
            ['name' => 'ORGM'],

        ];

        foreach ($data as $item) {
            Area::create($item);
        }
    }
}
