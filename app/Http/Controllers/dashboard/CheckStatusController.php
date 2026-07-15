<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Models\Check;
use Inertia\Inertia;

class CheckStatusController extends Controller
{
    public function index(Request $req) {
        $checks = [
            'unverified' => Check::query()
                ->with(['employee', 'attachments'])
                ->whereHas('employee', function ($query) use ($req) {
                    $query->where('full_name', 'LIKE', '%' . $req->string('search') . '%');
                })
                ->whereNull('verified_user_id')
                ->orderBy('created_at', 'DESC')
                ->paginate(20),
            'verified' => Check::query()
                ->with(['employee', 'attachments', 'verified_user'])
                ->whereHas('employee', function ($query) use ($req) {
                    $query->where('full_name', 'LIKE', '%' . $req->string('search') . '%');
                })
                ->whereNotNull('verified_user_id')
                ->orderBy('verified_at', 'DESC')
                ->paginate(20),
            'removed' => Check::query()
                ->with(['employee', 'attachments', 'verified_user'])
                ->whereHas('employee', function ($query) use ($req) {
                    $query->where('full_name', 'LIKE', '%' . $req->string('search') . '%');
                })
                ->onlyTrashed()
                ->orderBy('created_at', 'DESC')
                ->paginate(20),
        ];

        return Inertia::render('dashboard/check-status/index', [
            'page_title' => 'Checks',
            'navigation' => 'sidebar',
            'checks' => $checks,
        ]);
    }

    public function update(Request $req, Check $check) {
        $req->validate([
            'check_id' => ['required', 'exists:checks,id'],
            'redirect' => ['nullable']
        ]);



        if ($check) {
            $check->update([
                'verified_user_id' => Auth::user()->id,
                'verified_at' => now(),
            ]);
        }
        return back()
            ->with('success',['title' => 'Check Verified', 'message' => 'The check has been successfully verified.']);
    }
}
