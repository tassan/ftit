<?php

use App\Http\Controllers\DiagnosticoAiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and all will be
| assigned to the \"api\" middleware group. The URI prefix is \"/api\".
|
*/

Route::post('/diagnostico-ia', [DiagnosticoAiController::class, 'store'])
    ->name('api.diagnostico-ia');

