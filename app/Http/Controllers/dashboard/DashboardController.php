<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Check;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response {
        $now = Carbon::now();
        $current_start = $now->copy()->startOfMonth();
        $current_end = $now->copy()->endOfMonth();
        $previous_start = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $previous_end = $now->copy()->subMonthNoOverflow()->endOfMonth();

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

        $recent_active_employees = Employee::query()
            ->whereIn('id', $active_employee_ids_this_month)
            ->with(['office', 'college'])
            ->withCount('checks')
            ->withMax('checks', 'created_at')
            ->orderBy('checks_max_created_at', 'DESC')
            ->limit(8)
            ->get();

        $recent_checks = Check::query()
            ->with(['employee:id,full_name'])
            ->withCount('attachments')
            ->orderBy('created_at', 'DESC')
            ->limit(8)
            ->get();


        return Inertia::render('dashboard/index', [
            'page_title' => 'Dashboard',
            'sidebar' => true,
            'stats' => [
                'active_checks' => $active_checks,
                'active_employees' => $active_employees,
            ],
            'recent_active_employees' => $recent_active_employees,
            'recent_checks' => $recent_checks,
        ]);
    }
}
