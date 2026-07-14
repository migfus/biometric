<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CameraController extends Controller
{
    public function index(Request $req): Response {
        return Inertia::render('camera/index', [
            'page_title' => 'Camera',
            'navigation' => 'camera',
        ]);
    }
}
