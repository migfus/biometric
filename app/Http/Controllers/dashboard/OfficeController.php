<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OfficeController extends Controller
{
    public function index(Request $req)
    {
        $req->validate([
            'search' => ['nullable']
        ]);

        $offices = Office::query()
            ->where('name', 'LIKE', '%' . $req->string('search') . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return Inertia::render('dashboard/offices/index', [
            'page_title' => 'Offices',
            'sidebar' => true,
            'offices' => $offices
        ]);
    }
}
