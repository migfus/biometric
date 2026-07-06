<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/rephrase', [\App\Http\Controllers\RephraseController::class, 'rephrase']);

// Attachment deletion uses cookie-based client_uuid validation and requires
// the `web` middleware (cookie decryption). Move this route to routes/web.php.
//
