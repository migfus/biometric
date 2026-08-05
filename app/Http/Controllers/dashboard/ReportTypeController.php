<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\ReportType;
use Inertia\Inertia;
use Inertia\Response;

class ReportTypeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('dashboard/report-types/index', [
            'page_title' => 'Report Types',
            'navigation' => 'sidebar',

            'report_types' => ReportType::query()
                ->orderBy('name', 'ASC')
                ->withCount('reports')
                ->paginate(20),
        ]);
    }
}
