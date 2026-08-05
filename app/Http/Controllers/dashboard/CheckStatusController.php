<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\CheckStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckStatusController extends Controller
{
    public function index(Request $req)
    {
        return Inertia::render('dashboard/check-status/index', [
            'page_title' => 'Checks',
            'navigation' => 'sidebar',
            'check_statuses' => CheckStatus::orderBy('created_at', 'DESC')->paginate(20),
        ]);
    }
}
