<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\EmploymentType;
use Inertia\Inertia;
use Inertia\Response;

class EmploymentTypeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('dashboard/employment-types/index', [
            'page_title' => 'Employment Types',
            'navigation' => 'sidebar',

            'employment_types' => EmploymentType::query()
                ->orderBy('name', 'ASC')
                ->withCount('employees')
                ->paginate(20),
        ]);
    }
}
