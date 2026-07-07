<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index(Request $req)
    {
        $req->validate([
            'search' => ['nullable']
        ]);

        $employees = Employee::query()
            ->where('full_name', 'LIKE', '%' . $req->string('search') . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return Inertia::render('dashboard/employees/index', [
            'page_title' => 'Employees',
            'sidebar' => true,
            'departments' => $employees
        ]);
    }
}
