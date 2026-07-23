<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Inertia\{Inertia, Response};

use App\Models\{Employee, Check};

class DashboardController extends Controller
{
    public function index(): Response {
        $now = Carbon::now();
        $current_start = $now->copy()->startOfMonth();
        $current_end = $now->copy()->endOfMonth();
        $previous_start = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $previous_end = $now->copy()->subMonthNoOverflow()->endOfMonth();
        $pending_verifications = Check::where('verified_at', null)->count();

        // SECTION: STATS
        $active_checks = [
            'this_month' => Check::query()
                ->whereBetween('created_at', [$current_start, $current_end])
                ->count(),
            'previous_month' => Check::query()
                ->whereBetween('created_at', [$previous_start, $previous_end])
                ->count(),
        ];

        $active_employees = [
            'this_month' => Check::query()
                ->whereBetween('created_at', [$current_start, $current_end])
                ->distinct('employee_id')
                ->count('employee_id'),
            'previous_month' => Check::query()
                ->whereBetween('created_at', [$previous_start, $previous_end])
                ->distinct('employee_id')
                ->count('employee_id'),
        ];

        // SECTION: RECENT ACTIVE EMPLOYEES
        $active_employee_ids_this_month = Check::query()
            ->whereBetween('created_at', [$current_start, $current_end])
            ->distinct()
            ->pluck('employee_id');

        $active_employees = Employee::query()
            ->whereIn('id', $active_employee_ids_this_month)
            ->with(['office', 'college', 'checks'])
            ->withCount('checks')
            ->withMax('checks', 'created_at')
            ->orderBy('checks_max_created_at', 'DESC')
            ->paginate(10);

        $unverified_checks = Check::query()
            ->where('verified_at', null)
            ->with(['employee.office','employee.college', 'attachments'])
            ->withCount('attachments')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);


        return Inertia::render('dashboard/index', [
            'page_title' => 'Dashboard',
            'navigation' => 'sidebar',


            'stats' => [
                'active_checks' => $active_checks,
                'active_employees' => $active_employees,
                'pending_verifications' => $pending_verifications,
                'employees_count' => Employee::get()->count(),
                'checks_count' => Check::get()->count()
            ],

            'active_employees' => $active_employees,
            'unverified_checks' => $unverified_checks,
        ]);
    }
}
