<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\Employee;
use App\Models\Office;
use Illuminate\Database\Seeder;
use SplFileObject;

class EmployeesSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/employees.csv');

        if (! is_file($csvPath)) {
            return;
        }

        $file = new SplFileObject($csvPath);
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::DROP_NEW_LINE | SplFileObject::SKIP_EMPTY);

        $header = $file->fgetcsv();

        if (! is_array($header) || $header === [null]) {
            return;
        }

        $header = array_map(fn ($value): string => $this->normalizeCsvValue($value), $header);
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]) ?? $header[0];

        $officeIds = [];
        $collegeIds = [];
        $employeesById = [];
        $now = now();

        foreach ($file as $record) {
            if (! is_array($record) || $record === [null]) {
                continue;
            }

            $record = array_pad($record, count($header), null);
            $row = array_combine($header, $record);

            if ($row === false) {
                continue;
            }

            $employeeId = $this->normalizeCsvValue($row['id'] ?? '');
            $fullName = $this->normalizeCsvValue($row['full_name'] ?? '');
            $officeName = $this->normalizeCsvValue($row['office'] ?? '');
            $collegeName = $this->normalizeCsvValue($row['college'] ?? '');
            $email = $this->normalizeCsvValue($row['email'] ?? '');

            if ($employeeId === '' || $officeName === '') {
                continue;
            }

            $officeId = $officeIds[$officeName] ?? null;
            if ($officeId === null) {
                $officeId = Office::query()->firstOrCreate(['name' => $officeName])->id;
                $officeIds[$officeName] = $officeId;
            }

            $collegeId = null;
            if ($collegeName !== '') {
                $collegeId = $collegeIds[$collegeName] ?? null;

                if ($collegeId === null) {
                    $collegeId = College::query()->firstOrCreate(['name' => $collegeName])->id;
                    $collegeIds[$collegeName] = $collegeId;
                }
            }

            $employeesById[$employeeId] = [
                'id' => $employeeId,
                'full_name' => $fullName !== '' ? $fullName : null,
                'email' => $email !== '' ? $email : null,
                'office_id' => $officeId,
                'college_id' => $collegeId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($employeesById === []) {
            return;
        }

        Employee::query()->upsert(
            array_values($employeesById),
            ['id'],
            ['full_name', 'email', 'office_id', 'college_id', 'updated_at']
        );
    }

    private function normalizeCsvValue(mixed $value): string
    {
        $text = trim((string) $value);

        if ($text === '') {
            return '';
        }

        if (function_exists('mb_check_encoding') && ! mb_check_encoding($text, 'UTF-8') && function_exists('mb_convert_encoding')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8, Windows-1252, ISO-8859-1');
        }

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'UTF-8//IGNORE', $text);

            if ($converted !== false) {
                $text = $converted;
            }
        }

        return trim($text);
    }
}
