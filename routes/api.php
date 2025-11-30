<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtifactController;

// Definimos las rutas de los endpooints
Route::post('/clue', [ArtifactController::class, 'clue']);
Route::get('/stats', [ArtifactController::class, 'stats']);