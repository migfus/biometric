<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    HomeController,
    AttachmentController,
    CheckController,
    ForgotController,
    LoginController,
    RecordController,
    CameraController,
};
use App\Http\Controllers\dashboard\{
    DashboardController,
    CheckController as DashboardCheckController,
    EmployeeController,
    OfficeController,
    UserController,
    CollegeController,
    ProfileController,
    CheckStatusController,
};

Route::group([], function() {
    Route::resource('/', HomeController::class)->only(['index', 'store']);
    Route::resource('/camera', CameraController::class)->only(['index']);
    Route::resource('/records', RecordController::class)->only(['index', 'destroy', 'update']);

    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
    // Route::patch('/checks/{check}/ip-location', [CheckController::class, 'updateIpLocation'])->name('checks.update-ip-location');
    Route::resource('/checks', CheckController::class)->only(['destroy']);

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
        Route::resource('/checks', DashboardCheckController::class)->only(['index', 'show', 'destroy', 'update']);
        Route::resource('/employees', EmployeeController::class);
        Route::resource('/offices', OfficeController::class);
        Route::resource('/colleges', CollegeController::class);
        Route::resource('/users', UserController::class);
        Route::resource('/profile', ProfileController::class)->only(['index', 'store']);
    });
});
