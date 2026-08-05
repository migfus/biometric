<?php

use App\Http\Controllers\dashboard\BiometricDeviceStatusController;
use App\Http\Controllers\dashboard\CheckStatusController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\EmploymentTypeController;
use App\Http\Controllers\dashboard\NotificationController;
use App\Http\Controllers\dashboard\ReportController as DashboardReportController;
use App\Http\Controllers\dashboard\ReportTypeController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::resource('/', HomeController::class)->only(['index', 'store']);
    Route::redirect('/', '/report');
    Route::resource('/report', ReportController::class)->only(['index', 'store']);

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

        Route::resource('/biometric-device-statuses', BiometricDeviceStatusController::class)->only(['index', 'destroy']);
        Route::resource('/employment-types', EmploymentTypeController::class)->only(['index', 'destroy']);
        Route::resource('/check-statuses', CheckStatusController::class)->only(['index', 'destroy']);
        Route::resource('/report-types', ReportTypeController::class)->only(['index', 'destroy']);

        Route::resource('/notifications', NotificationController::class)->only(['index', 'destroy']);
    });
});
