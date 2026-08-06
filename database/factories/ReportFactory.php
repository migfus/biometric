<?php

namespace Database\Factories;

use App\Models\BiometricDevice;
use App\Models\CheckStatus;
use App\Models\Employee;
use App\Models\Report;
use App\Models\ReportType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $employeeId = Employee::query()->inRandomOrder()->value('id');
        if ($employeeId === null) {
            $employeeId = 'EMP-'.Str::upper(Str::random(8));

            DB::table('employees')->insert([
                'id' => $employeeId,
                'full_name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $checkStatusId = CheckStatus::query()->inRandomOrder()->value('id');
        if ($checkStatusId === null) {
            $checkStatusId = DB::table('check_statuses')->insertGetId([
                'name' => fake()->unique()->words(2, true),
                'icon' => fake()->optional()->word(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $reportTypeId = ReportType::query()->inRandomOrder()->value('id');
        if ($reportTypeId === null) {
            $reportTypeId = DB::table('report_types')->insertGetId([
                'name' => fake()->unique()->words(2, true),
                'icon' => fake()->optional()->word(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $biometricDeviceId = BiometricDevice::query()->inRandomOrder()->value('id');
        if ($biometricDeviceId === null) {
            $biometricDeviceId = DB::table('biometric_devices')->insertGetId([
                'name' => 'Device '.Str::upper(Str::random(6)),
                'serial' => 'SN-'.Str::upper(Str::random(10)),
                'model' => fake()->optional()->word(),
                'user_count' => fake()->numberBetween(0, 200),
                'fingerprint_count' => fake()->numberBetween(0, 200),
                'ip_address' => fake()->unique()->ipv4(),
                'port' => fake()->numberBetween(1000, 65000),
                'status_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return [
            'employee_id' => $employeeId,
            'biometric_device_id' => $biometricDeviceId,
            'report_type_id' => $reportTypeId,
            'check_status_id' => $checkStatusId,
            'description' => fake()->paragraph(2),
            'action_taken' => fake()->optional()->sentence(),
            'browser_id' => Str::uuid(),
            'ip_address' => fake()->optional()->ipv4(),
            'os' => fake()->optional()->word(),
        ];
    }

    public function withRelations(): static
    {
        return $this->afterCreating(function (Report $report): void {
            $report->load(['employee', 'checkStatus', 'reportType', 'biometricDevice']);
        });
    }
}
