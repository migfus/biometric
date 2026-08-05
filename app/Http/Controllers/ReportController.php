<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('report/index', [
            'page_title' => 'Report',
            'offices' => $this->getCachedOffice(),
        ]);
    }

    private function getCachedOffice()
    {
        return Cache::remember('office', now()->addMinutes(60), function () {
            return Office::all();
        });
    }

    private function getCachedEmploymentTypes()
    {
        return Cache::remember('employment_types', now()->addMinutes(60), function () {
            return [
                'Regular',
                'Contractual',
                'Job Order',
                'Casual',
            ];
        });
    }
}
