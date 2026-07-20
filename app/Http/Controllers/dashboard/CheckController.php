<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CheckController extends Controller
{
    public function index(Request $req): Response
    {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $checks = Check::query()
            ->where('work_description', 'LIKE', '%'.$req->string('search').'%')
            ->with(['attachments', 'employee.office', 'employee.college',  'verified_user'])
            ->orderBy('created_at', 'DESC')
            ->withTrashed()
            ->paginate(20);

        return Inertia::render('dashboard/checks/index', [
            'page_title' => 'Checks',
            'navigation' => 'sidebar',
            'checks' => $checks,
        ]);
    }

    public function show(int $check): Response
    {
        $check_data = Check::withTrashed()
            ->with(['employee', 'attachments'])
            ->findOrFail($check);

        return Inertia::render('dashboard/checks/show', [
            'page_title' => 'Check Details',
            'navigation' => 'sidebar',
            'check' => $check_data,
        ]);
    }

    public function update(Request $req, int $check): RedirectResponse
    {
        $val = $req->validate([
            'type' => ['required', 'in:verify,recover'],
        ]);

        return match ($val['type']) {
            'verify' => $this->updateVerify($req, $check),
            'recover' => $this->updateRecover($check),
            default => back()
                ->with('error', ['title' => 'Type', 'content' => 'Type error.'])
        };
    }

    protected function updateVerify(Request $req, int $check): RedirectResponse
    {
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

        $is_approved = ! is_null($check_data->verified_user_id);
        $check_data->verified_user_id = $is_approved ? null : $req->user()->id;
        $check_data->save();

        return back()
            ->with('success', [
                'title' => $is_approved ? 'Unverified' : 'Verified',
                'content' => $is_approved
                    ? 'The check was unverified successfully.'
                    : 'The check was verified successfully.',
            ]);
    }

    protected function updateRecover(int $check): RedirectResponse
    {
        $check_data = Check::withTrashed()->findOrFail($check);

        if (! $check_data->trashed()) {
            return to_route('dashboard.checks.index')
                ->with('error', [
                    'title' => 'Already active',
                    'content' => 'This check is not deleted.',
                ]);
        }

        $check_data->restore();

        return back()
            ->with('success', [
                'title' => 'Check recovered',
                'content' => 'The check was recovered successfully.',
            ]);
    }

    public function destroy(int $check): RedirectResponse
    {
        $check_data = Check::withTrashed()->findOrFail($check);

        $attachments = $check_data->attachments()->withTrashed()->get();

        foreach ($attachments as $attachment) {
            $file_path = public_path(ltrim((string) $attachment->file_location, '/'));

            if (is_file($file_path)) {
                @unlink($file_path);
            }
        }

        $check_data->forceDelete();

        return back()
            ->with('success', [
                'title' => 'Check deleted',
                'content' => 'The check was removed successfully.',
            ]);
    }

    public function print(Request $req): StreamedResponse
    {
        $req->validate([
            'search' => ['nullable'],
        ]);

        $employees = Check::query()
            ->orderBy('created_at', 'DESC')
            ->with(['employee.office', 'employee.college', 'verified_user'])
            ->withCount('attachments')
            ->whereHas('employee', fn ($q) => $q->where('full_name', 'LIKE', '%'.$req->string('search').'%'))
            ->get();

        $filename = 'checks-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($employees): void {
            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['ID', 'Employee', 'Check', 'Time', 'Office', 'College', 'Attachments Count', 'Verified By', 'Verified At', 'location', 'OS', 'Date']);

            foreach ($employees as $check) {
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
