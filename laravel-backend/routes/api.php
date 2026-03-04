<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmitController;
use App\Http\Controllers\DiagnosisController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Healthcheck or default example route (optional)
Route::get('/ping', function (Request $request) {
    return ['ok' => true];
});

// FTIT APIs migrated from public/api/*.php
Route::post('/submit', [SubmitController::class, 'handle']);
Route::post('/diagnostico-ia', [DiagnosisController::class, 'handle']);

