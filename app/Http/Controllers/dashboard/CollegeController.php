<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\College;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CollegeController extends Controller
{
    public function index(Request $req)
    {
        $req->validate([
            'search' => ['nullable']
        ]);

        $colleges = College::query()
            ->where('name', 'LIKE', '%' . $req->string('search') . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return Inertia::render('dashboard/colleges/index', [
            'page_title' => 'Checks',
            'sidebar' => true,
            'colleges' => $colleges
        ]);
    }
}
