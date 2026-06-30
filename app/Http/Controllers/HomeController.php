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

    }
}
