<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{HomeController, AttachmentController, CheckController, LoginController, ProfileController};
use App\Http\Controllers\dashboard\{DashboardController, CheckController as DashboardCheckController, EmployeeController, DepartmentController, UserController, CollegeController};

Route::resource('/', HomeController::class)->only(['index', 'store']);
Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
Route::resource('/checks', CheckController::class)->only(['destroy']);

Route::middleware('guest')->group(function () {
    Route::resource('/login', LoginController::class)->only(['index', 'store']);
});


Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');

    Route::group(['prefix' => '/dashboard', 'as' => 'dashboard.'], function () {
        Route::resource('/', DashboardController::class)->only(['index']);
        Route::resource('/checks', DashboardCheckController::class)->only(['index']);
        Route::resource('/employees', EmployeeController::class)->only(['index']);
        Route::resource('/departments', DepartmentController::class)->only(['index']);
        Route::resource('/colleges', CollegeController::class)->only(['index']);
        Route::resource('/users', UserController::class)->only(['index']);
        Route::resource('/profile', ProfileController::class)->only(['index']);
    });
});
