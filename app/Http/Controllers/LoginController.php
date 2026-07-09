<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\{RedirectResponse, Request};
use Inertia\{Inertia, Response};

use Auth;

class LoginController extends Controller
{
    public function index() : Response {
        return Inertia::render('login/index', [
            'page_title' => 'Login',
        ]);
    }

    public function store(LoginRequest $req) : RedirectResponse {
        $req->authenticate();

        $req->session()->regenerate();

        return redirect()->intended(route('dashboard.index', absolute: false))
            ->with('success', [
                    'title' => 'Logged In',
                    'content' => 'Welcome back.',
            ]);
    }

    public function logout(Request $req) : RedirectResponse {
        Auth::guard('web')->logout();

        $req->session()->invalidate();

        $req->session()->regenerateToken();

        return redirect('/')
            ->with('success', [
                    'title' => 'Logged Out',
                    'content' => 'Successfuly Logged Out.',
            ]);
    }
}
