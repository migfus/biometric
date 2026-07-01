<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index() {
        return Inertia::render('index', [
            'page_title' => 'Log'
        ]);
    }

    public function store(Request $req) {
        $val = $req->validate([
            'employee_no' => ['required', 'min:9'],
            'full_name' => ['required', 'min:8'],
            'department' => ['required'],
            'check' => ['required'],
            'work_description' => ['required', 'min:12'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['mimes:jpg,jpeg,png', 'max:2048']
        ]);

        dd($val);
    }
}
