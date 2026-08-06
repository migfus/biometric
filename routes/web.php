<?php

use App\Http\Controllers\dashboard\{
    BiometricDeviceStatusController,
    EmploymentTypeController,
    ReportTypeController,
    CheckStatusController,
    DashboardController,
    EmployeeController,
    NotificationController,
    ReportController as DashboardReportController
};
use App\Http\Controllers\{
    ForgotController,
    HomeController,
    LoginController,
    ReportController,
    CameraController
};
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::resource('/', HomeController::class)->only(['index', 'store']);
    Route::redirect('/', '/reports/create');
    Route::resource('/reports', ReportController::class)->only(['index', 'store', 'create'])->middleware('throttle:1,0.083');
    Route::resource('/camera', CameraController::class)->only(['index', 'store']);

    Route::middleware('guest')->group(function () {
        Route::resource('/login', LoginController::class)->only(['index', 'store']);
    });
    Route::resource('/forgot', ForgotController::class)->only(['index', 'store', 'show', 'edit', 'update']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');

    Route::group(['prefix' => '/dashboard', 'as' => 'dashboard.'], function () {
        Route::resource('/', DashboardController::class)->only(['index']);
        Route::resource('/reports', DashboardReportController::class)->only(['index', 'store', 'show', 'destroy']);
        Route::resource('/employees', EmployeeController::class)->only(['index', 'create']);

        Route::resource('/biometric-device-statuses', BiometricDeviceStatusController::class)->only(['index', 'destroy']);
        Route::resource('/employment-types', EmploymentTypeController::class)->only(['index', 'destroy']);
        Route::resource('/check-statuses', CheckStatusController::class)->only(['index', 'destroy']);
        Route::resource('/report-types', ReportTypeController::class)->only(['index', 'destroy']);

        Route::resource('/notifications', NotificationController::class)->only(['index', 'destroy']);
    });
});
