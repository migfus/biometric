<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $req)
    {
        $val = $req->validate([
            'search' => ['nullable']
        ]);

        $users = User::query()
            ->where('name', 'LIKE', '%' . $req->string('search') . '%')
            ->orWhere('email', 'LIKE', '%' . $req->string('search') . '%')
            ->orderBy('name', 'DESC')
            ->paginate(20);

        return Inertia::render('dashboard/users/index', [
            'page_title' => 'Users',
            'sidebar' => true,
            'users' => $users
        ]);
    }
}
