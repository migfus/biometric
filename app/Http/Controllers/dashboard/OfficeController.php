<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Validation\Rule;
use Inertia\{Inertia, Response};

use App\Models\Office;
class OfficeController extends Controller
{
    public function index(Request $req) : Response {
        $req->validate([
            'search' => ['nullable']
        ]);

        $offices = Office::query()
            ->where('name', 'LIKE', '%' . $req->string('search') . '%')
            ->with(['employees' => fn($q) => $q->limit(4)->orderBy('created_at', 'DESC')])
            ->withCount('employees')
            ->orderBy('created_at', 'DESC')
            ->paginate(42);

        return Inertia::render('dashboard/offices/index', [
            'page_title' => 'Offices',
            'navigation' => 'sidebar',
            'offices' => $offices
        ]);
    }

    public function create() : Response {
        return Inertia::render('dashboard/offices/create', [
            'page_title' => 'Create Office',
            'navigation' => 'sidebar',
        ]);
    }

    public function store(Request $req): RedirectResponse {
        $req->validate([
            'name' => ['required', 'min:4', 'unique:offices,name'],
        ]);

        Office::create([
            'name' => $req->string('name'),
        ]);

        return back()
            ->with('success', [
                'title' => 'Office created',
                'content' => 'The office was created successfully.',
            ]);
    }

    public function edit(Office $office) : Response {
        return Inertia::render('dashboard/offices/edit', [
            'page_title' => 'Edit Office',
            'navigation' => 'sidebar',
            'office' => $office,
        ]);
    }

    public function update(Request $req, Office $office): RedirectResponse {
        $req->validate([
            'name' => ['required', 'min:4', Rule::unique('offices', 'name')->ignore($office->id)],
        ]);

        $office->name = $req->string('name');
        $office->save();

        return back()
            ->with('success', [
                'title' => 'Office updated',
                'content' => 'The office was updated successfully.',
            ]);
    }

    public function destroy(Office $office): RedirectResponse {
        $office->delete();

        return back()
            ->with('success', [
                'title' => 'Office deleted',
                'content' => 'The office was removed successfully.',
            ]);
    }

    // SECTION: Show List of Employees
    public function show(Request $req, Office $office) : Response {
        $req->validate([
            'search' => ['nullable']
        ]);

        $employees = $office->employees()
            ->where('full_name', 'LIKE', '%' . $req->string('search') . '%')
            ->with(['checks' => fn($q) => $q->limit(2)->orderBy('created_at', 'DESC')])
            ->orderBy('created_at', 'DESC')
            ->paginate(42);

        return Inertia::render('dashboard/offices/show', [
            'page_title' => 'Office Details',
            'navigation' => 'sidebar',
            'office' => $office,
            'employees' => $employees,
        ]);
    }

    // SECTION: Show List of Checks
    public function showChecks(Request $req, Office $office) : Response {
        $req->validate([
            'search' => ['nullable']
        ]);

        $employees_id = $office->employees()->where('full_name', 'LIKE', '%' . $req->string('search') . '%')->pluck('id');

        $checks = Check::whereIn('employee_id', $employees_id)
            ->with(['employee.college', 'employee.office', 'attachments', 'verified_user'])
            ->orderBy('created_at', 'DESC')
            ->paginate(42);

        return Inertia::render('dashboard/offices/showCheck', [
            'page_title' => 'Office Details',
            'navigation' => 'sidebar',
            'office' => $office,
            'checks' => $checks,
        ]);
    }
}
