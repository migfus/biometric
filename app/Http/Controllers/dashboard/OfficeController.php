<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Check;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OfficeController extends Controller
{
    public function index(Request $req): Response {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $offices = Office::query()
            ->where('name', 'LIKE', '%'.$req->string('search').'%')
            ->with(['employees' => fn ($q) => $q->limit(4)->orderBy('created_at', 'DESC')])
            ->withCount('employees')
            ->orderBy('created_at', 'DESC')
            ->paginate(42);

        return Inertia::render('dashboard/offices/index', [
            'page_title' => 'Offices',
            'navigation' => 'sidebar',
            'offices' => $offices,
        ]);
    }

    public function create(): Response {
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

        return to_route('dashboard.offices.index')
            ->with('success', [

                'content' => 'The office was created successfully.',
            ]);
    }

    public function edit(Office $office): Response {
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

        return to_route('dashboard.offices.index')
            ->with('success', [

                'content' => 'The office was updated successfully.',
            ]);
    }

    public function destroy(Office $office): RedirectResponse {
        $office->delete();

        return back()
            ->with('success', [

                'content' => 'The office was removed successfully.',
            ]);
    }

    // SECTION: Show List of Employees
    public function show(Request $req, Office $office): Response {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employees = $office->employees()
            ->where('full_name', 'LIKE', '%'.$req->string('search').'%')
            ->with(['checks' => fn ($q) => $q->limit(2)->orderBy('created_at', 'DESC')])
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
    public function showChecks(Request $req, Office $office): Response {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employees_id = $office->employees()->where('full_name', 'LIKE', '%'.$req->string('search').'%')->pluck('id');

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

    public function print(Request $req): StreamedResponse {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $offices = Office::query()
            ->where('name', 'LIKE', '%'.$req->string('search').'%')
            ->orderBy('created_at', 'DESC')
            ->withCount('employees')
            ->get(['id', 'name', 'created_at']);

        $filename = 'offices-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($offices): void {
            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['ID', 'Name', 'Employees', 'Created At']);

            foreach ($offices as $office) {
                fputcsv($stream, [
                    $office->id,
                    $office->name,
                    $office->employees_count,
                    optional($office->created_at)?->toDateTimeString(),
                ]);
            }

            fclose($stream);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function showCheckPrint(Request $req, Office $office): StreamedResponse {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employees_id = $office->employees()->where('full_name', 'LIKE', '%'.$req->string('search').'%')->pluck('id');

        $checks = Check::query()
            ->whereIn('employee_id', $employees_id)
            ->with(['attachments', 'verified_user', 'employee.office', 'employee.college'])
            ->withCount('attachments')
            ->where('work_description', 'LIKE', '%'.$req->string('search').'%')
            ->orderBy('created_at', 'DESC')
            ->get();

        $filename = $office->name.'-'.$office->id.'-checks-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($office, $checks): void {
            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['Office Information']);
            fputcsv($stream, ['ID', $office->id]);
            fputcsv($stream, ['Name', $office->name]);
            fputcsv($stream, ['Checks Count', count($checks)]);
            fputcsv($stream, ['Exported At', now()->toDateTimeString()]);
            fputcsv($stream, []);

            fputcsv($stream, ['ID', 'Employee', 'Check', 'Time', 'Office', 'College', 'Attachments Count', 'Verified By', 'Verified At', 'location', 'OS', 'Date']);

            foreach ($checks as $check) {
                fputcsv($stream, [
                    $check->id,
                    $check->employee?->full_name,
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
