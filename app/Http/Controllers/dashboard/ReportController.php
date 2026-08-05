<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('dashboard/reports/index', [
            'page_title' => 'Reports',
            'navigation' => 'sidebar',

            'reports' => Report::query()
                ->with(['reportType', 'employee', 'checkStatus'])
                ->orderBy('created_at', 'DESC')
                ->paginate(20),
        ]);
    }
}
