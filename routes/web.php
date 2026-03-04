<?php

use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ObrigadoController;
use App\Http\Controllers\PrivacyController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');

Route::get('/diagnostico', [DiagnosticoController::class, 'showForm'])
    ->name('diagnostico.show');

Route::get('/privacidade', [PrivacyController::class, 'show'])
    ->name('privacidade.show');

Route::get('/obrigado', [ObrigadoController::class, 'show'])
    ->name('obrigado.show');


