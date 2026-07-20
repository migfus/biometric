<?php

namespace Tests\Feature;

use App\Models\Check;
use App\Models\College;
use App\Models\Employee;
use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeChecksCsvDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_checks_csv_includes_employee_info_before_checks_data(): void
    {
        /** @var User $authUser */
        $authUser = User::factory()->createOne();

        $office = Office::query()->create([
            'name' => 'Main Office',
        ]);

        $college = College::query()->create([
            'name' => 'Engineering',
        ]);

        $employee = Employee::query()->create([
            'id' => '202600001',
            'full_name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'office_id' => $office->id,
            'college_id' => $college->id,
        ]);

        Check::query()->create([
            'browser_id' => '11111111-1111-1111-1111-111111111111',
            'ip_address' => '127.0.0.1',
            'ip_location' => 'Campus',
            'os' => 'Windows',
            'employee_id' => $employee->id,
            'verified_user_id' => $authUser->id,
            'verified_at' => now(),
            'check_in' => true,
            'work_description' => 'Prepared weekly report',
            'rephrase_count' => 0,
        ]);

        $response = $this->actingAs($authUser)->get(route('dashboard.employees.showPrint', [
            'employee' => $employee->id,
            'search' => 'Prepared',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $csv = $response->streamedContent();

        $this->assertStringContainsString('Employee Information', $csv);
        $this->assertStringContainsString("ID,{$employee->id}", $csv);
        $this->assertStringContainsString('Full Name,"John Doe"', $csv);
        $this->assertStringContainsString('Checks Count,1', $csv);
        $this->assertStringContainsString('ID,"Work Description",Time,Office,College,"Attachments Count","Verified By","Verified At",Location,OS,Date', $csv);
        $this->assertStringContainsString('Prepared weekly report', $csv);

        $this->assertTrue(
            strpos($csv, 'Employee Information') < strpos($csv, 'ID,"Work Description"'),
            'Employee information should be printed before checks data header.'
        );
    }
}
