<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Validation\Rule;
use Inertia\{Inertia, Response};

use App\Models\{College, Employee, Office};

class EmployeeController extends Controller
{
    public function index(Request $req) : Response {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employees = Employee::query()
            ->with(['office', 'college', 'checks' => fn($q) => $q->limit(1)->orderBy('created_at', 'DESC')])
            ->where('full_name', 'LIKE', '%' . $req->string('search') . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return Inertia::render('dashboard/employees/index', [
            'page_title' => 'Employees',
            'navigation' => 'sidebar',
            'employees' => $employees,
        ]);
    }

    public function create() : Response {
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
        if (!empty($val['college'])) {
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
                'title' => 'Employee created',
                'content' => 'The employee was created successfully.',
            ]);
    }

    public function edit(Employee $employee) : Response {
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
        if (!empty($val['college'])) {
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
                'title' => 'Employee updated',
                'content' => 'The employee was updated successfully.',
            ]);
    }

    public function destroy(Employee $employee): RedirectResponse {
        $employee->delete();

        return to_route('dashboard.employees.index')
            ->with('success', [
                'title' => 'Employee deleted',
                'content' => 'The employee was removed successfully.',
            ]);
    }

    public function show(Employee $employee): Response {
        return Inertia::render('dashboard/employees/show', [
            'page_title' => 'Employee Details',
            'navigation' => 'sidebar',
            'employee' => $employee->load(['office', 'college', 'checks' => fn($q) => $q->with('attachments')->orderBy('created_at', 'DESC')]),
        ]);
    }
}
