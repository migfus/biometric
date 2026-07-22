<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AblyController, RephraseController};


Route::post('/rephrase', [RephraseController::class, 'rephrase']);
Route::resource('/ably', AblyController::class)->only(['store']);
