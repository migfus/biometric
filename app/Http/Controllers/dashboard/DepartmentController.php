<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    public function index(Request $req)
    {
        $req->validate([
            'search' => ['nullable']
        ]);

        $departments = Department::query()
            ->where('name', 'LIKE', '%' . $req->string('search') . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return Inertia::render('dashboard/departments/index', [
            'page_title' => 'Departments',
            'sidebar' => true,
            'departments' => $departments
        ]);
    }
}
