<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\{HomeController, AttachmentController, CheckController, ProfileController, LoginController};

Route::resource('/', HomeController::class)->only(['index', 'store']);
Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
Route::resource('/checks', CheckController::class)->only(['destroy']);

Route::middleware('guest')->group(function () {
    Route::resource('/login', LoginController::class)->only(['index', 'store']);
});


Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// require __DIR__ . '/auth.php';
