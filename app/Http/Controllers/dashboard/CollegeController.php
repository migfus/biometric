<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Check;
use App\Models\College;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CollegeController extends Controller
{
    public function index(Request $req): Response
    {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $colleges = College::query()
            ->where('name', 'LIKE', '%'.$req->string('search').'%')
            ->with(['employees' => fn ($q) => $q->limit(4)->orderBy('created_at', 'DESC')])
            ->withCount('employees')
            ->orderBy('created_at', 'DESC')
            ->paginate(42);

        return Inertia::render('dashboard/colleges/index', [
            'page_title' => 'Colleges',
            'navigation' => 'sidebar',
            'colleges' => $colleges,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('dashboard/colleges/create', [
            'page_title' => 'Create College or Department',
            'navigation' => 'sidebar',
        ]);
    }

    public function store(Request $req): RedirectResponse
    {
        $req->validate([
            'name' => ['required', 'min:4', 'unique:colleges,name'],
        ]);

        College::create([
            'name' => $req->string('name'),
        ]);

        return back()
            ->with('success', [

                'content' => 'The college or department was created successfully.',
            ]);
    }

    public function edit(College $college): Response
    {
        return Inertia::render('dashboard/colleges/edit', [
            'page_title' => 'Edit College or Department',
            'navigation' => 'sidebar',
            'college' => $college,
        ]);
    }

    // DEBUG: Add auth user restriction only admin can delete
    public function destroy(College $college): RedirectResponse
    {
        $college->delete();

        return back()
            ->with('success', [

                'content' => 'The college or department was removed successfully.',
            ]);
    }

    public function update(Request $req, College $college): RedirectResponse
    {
        $req->validate([
            'name' => ['required', 'min:4', Rule::unique('colleges', 'name')->ignore($college->id)],
        ]);

        $college->name = $req->string('name');

        $college->save();

        return back()
            ->with('success', [

                'content' => 'The college or department was updated successfully.',
            ]);
    }

    // SECTION: Show List of Employees
    public function show(Request $req, College $college): Response
    {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employees = $college->employees()
            ->where('full_name', 'LIKE', '%'.$req->string('search').'%')
            ->with(['checks' => fn ($q) => $q->limit(2)->orderBy('created_at', 'DESC')])
            ->orderBy('created_at', 'DESC')
            ->paginate(42);

        return Inertia::render('dashboard/colleges/show', [
            'page_title' => 'College or Department Details',
            'navigation' => 'sidebar',
            'college' => $college,
            'employees' => $employees,
        ]);
    }

    // SECTION: Show List of Checks
    public function showChecks(Request $req, College $college): Response
    {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employees_id = $college->employees()->where('full_name', 'LIKE', '%'.$req->string('search').'%')->pluck('id');

        $checks = Check::whereIn('employee_id', $employees_id)
            ->with(['employee.college', 'employee.office', 'attachments', 'verified_user'])
            ->orderBy('created_at', 'DESC')
            ->paginate(42);

        return Inertia::render('dashboard/colleges/showCheck', [
            'page_title' => 'College or Department Details',
            'navigation' => 'sidebar',
            'college' => $college,
            'checks' => $checks,
        ]);
    }

    public function print(Request $req): StreamedResponse
    {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $colleges = College::query()
            ->where('name', 'LIKE', '%'.$req->string('search').'%')
            ->withCount('employees')
            ->orderBy('created_at', 'DESC')
            ->get(['id', 'name', 'created_at']);

        $filename = 'colleges-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($colleges): void {
            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['ID', 'Name', 'Employees', 'Created At']);

            foreach ($colleges as $college) {
                fputcsv($stream, [
                    $college->id,
                    $college->name,
                    $college->employees_count,
                    optional($college->created_at)?->toDateTimeString(),
                ]);
            }

            fclose($stream);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
