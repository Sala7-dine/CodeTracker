<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Support\Facades\Route;

// Routes API pour CodeTrack
Route::post('/track', [ActivityController::class, 'track']);
Route::get('/data', [ActivityController::class, 'getActivities']);
Route::get('/stats/{project}', [ActivityController::class, 'getProjectStats']);

// Route pour récupérer les projets (avec auth)
Route::get('/projects', [ProjectController::class, 'getUserProjects']);

// Routes publiques (sans auth)
Route::get('/projects-public', function() {
    // Pour les démos ou tests sans authentification
    return response()->json([]);
});