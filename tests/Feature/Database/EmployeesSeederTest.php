<?php

namespace Tests\Feature\Database;

use App\Models\Employee;
use Database\Seeders\EmployeesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeesSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_employees_offices_and_colleges_from_csv(): void
    {
        $this->seed(EmployeesSeeder::class);

        $this->assertDatabaseHas('offices', [
            'name' => 'AGRICULTURAL ECONOMICS',
        ]);

        $this->assertDatabaseHas('colleges', [
            'name' => 'COLLEGE OF AGRICULTURE',
        ]);

        $this->assertDatabaseHas('employees', [
            'id' => 'F-1013-AGG',
            'full_name' => 'ABAO, GRETCHEN GREGORIO',
            'email' => null,
        ]);

        $employee = Employee::query()->find('F-1013-AGG');

        $this->assertNotNull($employee);
        $this->assertNotNull($employee->office);
        $this->assertSame('AGRICULTURAL ECONOMICS', $employee->office->name);
        $this->assertNotNull($employee->college);
        $this->assertSame('COLLEGE OF AGRICULTURE', $employee->college->name);

        $this->assertDatabaseMissing('employees', [
            'id' => '',
        ]);
    }
}
