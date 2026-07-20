<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\dashboard\CheckController as DashboardCheckController;
use App\Http\Controllers\dashboard\CheckStatusController;
use App\Http\Controllers\dashboard\CollegeController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\EmployeeController;
use App\Http\Controllers\dashboard\OfficeController;
use App\Http\Controllers\dashboard\ProfileController;
use App\Http\Controllers\dashboard\UserController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RecordController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::resource('/', HomeController::class)->only(['index', 'store']);
    Route::resource('/camera', CameraController::class)->only(['index']);
    Route::resource('/records', RecordController::class)->only(['index', 'destroy', 'update']);

    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    Route::middleware('guest')->group(function () {
        Route::resource('/login', LoginController::class)->only(['index', 'store']);
    });
    Route::resource('/forgot', ForgotController::class)->only(['index', 'store', 'show', 'edit', 'update']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');

    Route::group(['prefix' => '/dashboard', 'as' => 'dashboard.'], function () {
        Route::resource('/', DashboardController::class)->only(['index']);
        Route::resource('/check-status', CheckStatusController::class)->only(['index', 'update']);

        Route::get('/checks/print', [DashboardCheckController::class, 'print'])->name('checks.print');
        Route::resource('/checks', DashboardCheckController::class)->only(['index', 'show', 'destroy', 'update']);

        Route::get('/employees/print', [EmployeeController::class, 'print'])->name('employees.print');
        Route::resource('/employees', EmployeeController::class);
        Route::get('/employees/{employee}/checks', [EmployeeController::class, 'showPrint'])->name('employees.showPrint');

        Route::get('/offices/print', [OfficeController::class, 'print'])->name('offices.print');
        Route::resource('/offices', OfficeController::class);
        Route::get('/offices/{office}/checks', [OfficeController::class, 'showChecks'])->name('offices.showChecks');
        Route::get('/offices/{office}/checks/print', [OfficeController::class, 'showCheckPrint'])->name('offices.showCheckPrint');

        Route::get('/colleges/print', [CollegeController::class, 'print'])->name('colleges.print');
        Route::resource('/colleges', CollegeController::class);
        Route::get('/colleges/{college}/checks', [CollegeController::class, 'showChecks'])->name('colleges.showChecks');

        Route::get('/users/print', [UserController::class, 'print'])->name('users.print');
        Route::resource('/users', UserController::class);

        Route::resource('/profile', ProfileController::class)->only(['index', 'store']);
    });
});
