<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Employee;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    public function index(Request $req): Response {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employees = Employee::query()
            ->with(['office', 'college', 'checks' => fn ($q) => $q->limit(4)->orderBy('created_at', 'DESC')])
            ->where('full_name', 'LIKE', '%'.$req->string('search').'%')
            ->orderBy('created_at', 'DESC')
            ->paginate(42);

        return Inertia::render('dashboard/employees/index', [
            'page_title' => 'Employees',
            'navigation' => 'sidebar',
            'employees' => $employees,
        ]);
    }

    public function create(): Response {
        return Inertia::render('dashboard/employees/create', [
            'page_title' => 'Create Employee',
            'navigation' => 'sidebar',
        ]);
    }

    public function store(Request $req): RedirectResponse {
        $val = $req->validate([
            'id' => ['required', 'min:9', Rule::unique('employees', 'id')],
            'full_name' => ['required', 'min:4'],
            'college' => ['nullable'],
            'office' => ['required'],
            'email' => ['nullable', 'email'],
        ]);

        $office = Office::firstOrCreate(
            ['name' => $val['office']],
            ['name' => $val['office']],
        );

        $college = null;
        if (! empty($val['college'])) {
            $college = College::firstOrCreate(
                ['name' => $val['college']],
                ['name' => $val['college']],
            );
        }

        Employee::updateOrCreate(
            ['id' => $val['id']],
            [
                'full_name' => $val['full_name'],
                'college_id' => $college?->id,
                'office_id' => $office->id,
                'email' => $val['email'] ?? null,
            ]
        );

        return to_route('dashboard.employees.index')
            ->with('success', [

                'content' => 'The employee was created successfully.',
            ]);
    }

    public function edit(Employee $employee): Response {
        return Inertia::render('dashboard/employees/edit', [
            'page_title' => 'Edit Employee',
            'navigation' => 'sidebar',
            'employee' => $employee->load(['office', 'college']),
        ]);
    }

    public function update(Request $req, Employee $employee): RedirectResponse {
        $val = $req->validate([
            'id' => ['required', 'min:9', Rule::unique('employees', 'id')->ignore($employee->id)],
            'full_name' => ['required', 'min:4'],
            'college' => ['nullable'],
            'office' => ['required'],
            'email' => ['nullable', 'email'],
        ]);

        $office = Office::firstOrCreate(
            ['name' => $val['office']],
            ['name' => $val['office']],
        );

        $college = null;
        if (! empty($val['college'])) {
            $college = College::firstOrCreate(
                ['name' => $val['college']],
                ['name' => $val['college']],
            );
        }

        $employee->id = $val['id'];
        $employee->full_name = $val['full_name'];
        $employee->college_id = $college?->id;
        $employee->office_id = $office->id;
        $employee->email = $val['email'] ?? null;
        $employee->save();

        return to_route('dashboard.employees.index')
            ->with('success', [
                'content' => 'The employee was updated successfully.',
            ]);
    }

    public function destroy(Employee $employee): RedirectResponse {
        $employee->delete();

        return back()
            ->with('success', [

                'content' => 'The employee was removed successfully.',
            ]);
    }

    public function show(Request $req, Employee $employee): Response {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employee = $employee->load([
            'office',
            'college',
        ]);

        $checks = $employee->checks()
            ->with(['attachments', 'verified_user', 'employee.office', 'employee.college'])
            ->where('work_description', 'LIKE', '%'.$req->string('search').'%')
            ->orderBy('created_at', 'DESC')
            ->paginate(42);

        return Inertia::render('dashboard/employees/show', [
            'page_title' => 'Employee Details',
            'navigation' => 'sidebar',
            'employee' => $employee,
            'checks' => $checks,
        ]);
    }

    public function print(Request $req): StreamedResponse {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employees = Employee::query()
            ->where('full_name', 'LIKE', '%'.$req->string('search').'%')
            ->orderBy('created_at', 'DESC')
            ->with(['office', 'college'])
            ->withCount('checks')
            ->get(['id', 'full_name', 'email', 'office_id', 'college_id', 'created_at']);

        $filename = 'employees-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($employees): void {
            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['ID', 'Full Name', 'Email', 'Office', 'College', 'Checks Count', 'Created At']);

            foreach ($employees as $employee) {
                fputcsv($stream, [
                    $employee->id,
                    $employee->full_name,
                    $employee->email,
                    $employee->office?->name,
                    $employee->college?->name,
                    $employee->checks_count,
                    optional($employee->created_at)?->toDateTimeString(),
                ]);
            }

            fclose($stream);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function showPrint(Request $req, Employee $employee): StreamedResponse {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employee = $employee->load(['office', 'college'])->loadCount('checks');

        $checks = $employee->checks()
            ->with(['attachments', 'verified_user', 'employee.office', 'employee.college'])
            ->withCount('attachments')
            ->where('work_description', 'LIKE', '%'.$req->string('search').'%')
            ->orderBy('created_at', 'DESC')
            ->get();

        $filename = $employee->full_name.'-'.$employee->id.'-checks-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($employee, $checks): void {
            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['Employee Information']);
            fputcsv($stream, ['ID', $employee->id]);
            fputcsv($stream, ['Full Name', $employee->full_name]);
            fputcsv($stream, ['Email', $employee->email]);
            fputcsv($stream, ['Office', $employee->office?->name]);
            fputcsv($stream, ['College', $employee->college?->name]);
            fputcsv($stream, ['Checks Count', $employee->checks_count]);
            fputcsv($stream, ['Exported At', now()->toDateTimeString()]);
            fputcsv($stream, []);

            fputcsv($stream, ['ID', 'Check', 'Time', 'Office', 'College', 'Attachments Count', 'Verified By', 'Verified At', 'location', 'OS', 'Date']);

            foreach ($checks as $check) {
                fputcsv($stream, [
                    $check->id,
                    $check->check_in ? 'IN' : 'OUT',
                    $check->created_at ? Carbon::parse($check->created_at)->format('H:i:s') : '',
                    $check->employee?->office?->name,
                    $check->employee?->college?->name,
                    $check->attachments_count,
                    $check->verified_user?->name,
                    $check->verified_at ? Carbon::parse($check->verified_at)->format('Y-m-d H:i:s') : '',
                    $check->ip_location ?? $check->ip_address,
                    $check->os,
                    optional($check->created_at)?->format('Y-m-d'),
                ]);
            }

            fclose($stream);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
