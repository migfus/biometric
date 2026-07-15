<?php

use Illuminate\Support\Facades\Route;

Route::post('/rephrase', [\App\Http\Controllers\RephraseController::class, 'rephrase']);
