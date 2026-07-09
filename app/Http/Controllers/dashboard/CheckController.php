<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CheckController extends Controller
{
    public function index(Request $req): Response {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $checks = Check::query()
            ->where('work_description', 'LIKE', '%' . $req->string('search') . '%')
            ->with(['attachments:id,file_location', 'employee'])
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return Inertia::render('dashboard/checks/index', [
            'page_title' => 'Checks',
            'sidebar' => true,
            'checks' => $checks,
        ]);
    }

    public function create(): Response {
        return Inertia::render('dashboard/checks/create', [
            'page_title' => 'Create Check',
            'sidebar' => true,
        ]);
    }

    public function store(Request $req): RedirectResponse {
        $val = $req->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'check' => ['required', Rule::in(['Check In', 'Check Out'])],
            'work_description' => ['required', 'min:12'],
            'os' => ['nullable'],
            'ip_address' => ['nullable', 'ip'],
        ]);

        $browser_id = $this->getClientUUID($req);
        if (empty($browser_id)) {
            $browser_id = (string) Str::uuid();
        }

        Check::create([
            'browser_id' => $browser_id,
            'ip_address' => $val['ip_address'] ?? ($req->ip() ?? '127.0.0.1'),
            'ip_location' => null,
            'os' => (string) ($val['os'] ?? ($req->userAgent() ?? 'Unknown')),
            'employee_id' => $val['employee_id'],
            'check_in' => $val['check'] === 'Check In',
            'work_description' => $val['work_description'],
            'rephrase_count' => 0,
        ]);

        return to_route('dashboard.checks.index')
            ->with('success', [
                'title' => 'Check created',
                'content' => 'The check was created successfully.',
            ]);
    }

    public function show(Check $check): Response {
        return Inertia::render('dashboard/checks/show', [
            'page_title' => 'Check Details',
            'sidebar' => true,
            'check' => $check->load(['employee', 'attachments']),
        ]);
    }

    public function edit(Check $check): Response {
        return Inertia::render('dashboard/checks/edit', [
            'page_title' => 'Edit Check',
            'sidebar' => true,
            'check' => $check->load(['employee']),
        ]);
    }

    public function update(Request $req, Check $check): RedirectResponse {
        $val = $req->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'check' => ['required', Rule::in(['Check In', 'Check Out'])],
            'work_description' => ['required', 'min:12'],
            'os' => ['nullable'],
            'ip_address' => ['nullable', 'ip'],
        ]);

        $check->employee_id = $val['employee_id'];
        $check->check_in = $val['check'] === 'Check In';
        $check->work_description = $val['work_description'];
        $check->os = (string) ($val['os'] ?? $check->os);
        $check->ip_address = (string) ($val['ip_address'] ?? $check->ip_address);
        $check->save();

        return to_route('dashboard.checks.index')
            ->with('success', [
                'title' => 'Check updated',
                'content' => 'The check was updated successfully.',
            ]);
    }

    public function destroy(Check $check): RedirectResponse {
        $check->delete();

        return to_route('dashboard.checks.index')
            ->with('success', [
                'title' => 'Check deleted',
                'content' => 'The check was removed successfully.',
            ]);
    }
}
