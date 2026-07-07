<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $req)
    {


        return Inertia::render('dashboard/index', [
            'page_title' => 'Dashboard',
            'sidebar' => true,

        ]);
    }
}
