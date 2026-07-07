<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckController extends Controller
{
    public function index(Request $req)
    {
        $req->validate([
            'search' => ['nullable']
        ]);

        $checks = Check::query()
            ->where('work_description', 'LIKE', '%' . $req->string('search') . '%')
            ->with(['attachments:id,file_location', 'employee'])
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return Inertia::render('dashboard/checks/index', [
            'page_title' => 'Checks',
            'sidebar' => true,
            'check' => $checks
        ]);
    }
}
