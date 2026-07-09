<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    HomeController,
    AttachmentController,
    CheckController,
    ForgotController,
    LoginController,

};
use App\Http\Controllers\dashboard\{
    DashboardController,
    CheckController as DashboardCheckController,
    EmployeeController,
    OfficeController,
    UserController,
    CollegeController,
    ProfileController
};

Route::group([], function() {
    Route::resource('/', HomeController::class)->only(['index', 'store']);
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
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
        Route::resource('/checks', DashboardCheckController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        Route::resource('/employees', EmployeeController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy', 'show']);
        Route::resource('/offices', OfficeController::class)->only(['index', 'edit', 'destroy', 'create', 'store', 'update']);
        Route::resource('/colleges', CollegeController::class)->only(['index', 'edit', 'destroy', 'create', 'store', 'update']);
        Route::resource('/users', UserController::class)->only(['index', 'create', 'store', 'update', 'edit', 'destroy']);
        Route::resource('/profile', ProfileController::class)->only(['index', 'store']);
    });
});
