<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\{Inertia, Response};

use App\Models\Check;

class CheckController extends Controller
{
    public function index(Request $req): Response {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $checks = Check::query()
            ->where('work_description', 'LIKE', '%' . $req->string('search') . '%')
            ->with(['attachments', 'employee', 'verified_user'])
            ->orderBy('created_at', 'DESC')
            ->withTrashed()
            ->paginate(20);

        return Inertia::render('dashboard/checks/index', [
            'page_title' => 'Checks',
                'navigation' => 'sidebar',
            'checks' => $checks,
        ]);
    }

    public function create(): Response {
        return Inertia::render('dashboard/checks/create', [
            'page_title' => 'Create Check',
            'navigation' => 'sidebar',
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

    public function show(int $check): Response {
        $check_data = Check::withTrashed()
            ->with(['employee', 'attachments'])
            ->findOrFail($check);

        return Inertia::render('dashboard/checks/show', [
            'page_title' => 'Check Details',
                'navigation' => 'sidebar',
            'check' => $check_data,
        ]);
    }

    public function update(Request $req, int $check) : RedirectResponse {
        $val = $req->validate([
            'type' => ['required', 'in:verify,recover'],
        ]);

        return match ($val['type']) {
            'verify' => $this->updateVerify($req, $check),
            'recover' => $this->updateRecover($check),
            default => to_route('dashboard.checks.index')
                        ->with('error', ['title' => 'Type', 'content' => 'Type error.'])
        };
    }

    protected function updateVerify(Request $req, int $check): RedirectResponse {
        $check_data = Check::withTrashed()->findOrFail($check);

        if (! $req->user()) {
            abort(403);
        }

        if ($check_data->trashed()) {
            return to_route('dashboard.checks.index')
                ->with('error', [
                    'title' => 'Cannot verify',
                    'content' => 'Please recover this check before verifying it.',
                ]);
        }

        $is_approved = !is_null($check_data->verified_user_id);
        $check_data->verified_user_id = $is_approved ? null : $req->user()->id;
        $check_data->save();

        if($req->redirect) {
            return to_route($req->redirect)
                ->with('success', [
                    'title' => $is_approved ? 'Unverified' : 'Verified',
                    'content' => $is_approved
                        ? 'The check was unverified successfully.'
                        : 'The check was verified successfully.',
                ]);
        }

        return to_route('dashboard.checks.index')
            ->with('success', [
                'title' => $is_approved ? 'Unverified' : 'Verified',
                'content' => $is_approved
                    ? 'The check was unverified successfully.'
                    : 'The check was verified successfully.',
            ]);
    }

    protected function updateRecover(int $check): RedirectResponse {
        $check_data = Check::withTrashed()->findOrFail($check);

        if (! $check_data->trashed()) {
            return to_route('dashboard.checks.index')
                ->with('error', [
                    'title' => 'Already active',
                    'content' => 'This check is not deleted.',
                ]);
        }

        $check_data->restore();

        return to_route('dashboard.checks.index')
            ->with('success', [
                'title' => 'Check recovered',
                'content' => 'The check was recovered successfully.',
            ]);
    }

    public function destroy(int $check): RedirectResponse {
        $check_data = Check::withTrashed()->findOrFail($check);

        $attachments = $check_data->attachments()->withTrashed()->get();

        foreach ($attachments as $attachment) {
            $file_path = public_path(ltrim((string) $attachment->file_location, '/'));

            if (is_file($file_path)) {
                @unlink($file_path);
            }
        }

        $check_data->forceDelete();

        return to_route('dashboard.checks.index')
            ->with('success', [
                'title' => 'Check deleted',
                'content' => 'The check was removed successfully.',
            ]);
    }
}
